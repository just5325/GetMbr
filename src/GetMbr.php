<?php

/**
 * 使用方法:
 * 1.根据经纬度,获取'MBR'数据
 * @param float $lat 纬度
 * @param float $lon 经度
 * @param float $distance 距离(单位公里 如5就是5公里)
 * @return array 'MBR'数据
 * 使用实例:
 * $gpsdis = (new Getmbr)->Main($lat,$lon,$distance)
 *
 * 2.根据经纬度，计算2个点之间的距离。
 * @param float $lat1 纬度1
 * @param float $lon1 经度1
 * @param float $lat2 纬度2
 * @param float $lon2 经度2
 * @return float 距离（公里、千米）
 * 使用实例:
 * $distance = (new Getmbr)->Distance($lat1, $lon1, $lat2, $lon2)
 *
 * 说明:
 * 这个根据一个经纬度坐标、距离然后求另外一个经纬度坐标的作用，
 * 主要就是确定一个最小外包矩形(Minimum bounding rectangle，简称MBR)。
 * 例如，我要找一个坐标点(lat,lon)的5公里范围内的所有商户信息、景点信息等。
 * 这个MBR就是一个最大的范围，
 * 这个矩形是包含5公里范围内所有这些有效信息的一个最小矩形。
 * 利用公式，求出四个方向0度、90度、180度、270度方向上的四个坐标点就可以得到这个MBR。
 *
 * 如果有一个应用，表里存有100万的数据，数据包含一个lat、lon的经纬度信息。
 * 就可以先根据输入的经纬度和距离得到一个MBR，然后通过类似
SELECT Id
FROM IdInfoTable
WHERE latitude >= minLat AND latitude < maxLat
AND longitude >= minLon AND longitude < maxLon
 *
 */

namespace Hcg\GetMbr;

class GetMbr {

    //最大纬度值
    public $MaxLatitude;
    //最小纬度值
    public $MinLatitude;
    public $MaxLongitude;
    public $MinLongitude;
    const EARTH_RADIUS = 6371.0;//km 地球半径 平均值，千米

    /**
     * 获取'MBR'数据
     * @param float $lat 纬度
     * @param float $lon 经度
     * @param float $distance 距离(单位公里 如5就是5公里)
     * @return array 'MBR'数据
     * */
    public function Main($lat,$lon,$distance)
    {
        $this->GetMBR($lat, $lon, $distance);
        //返回'MBR'数据
        return [
            'MaxLatitude'=>$this->MaxLatitude,
            'MinLatitude'=>$this->MinLatitude,
            'MaxLongitude'=>$this->MaxLongitude,
            'MinLongitude'=>$this->MinLongitude
        ];
    }

    private function GetMBR($centorlatitude, $centorLogitude, $distance)
    {
        //以下为核心代码
        $range  = 180 / pi()* $distance / 6372.797; //里面的 $distance 就代表搜索 $distance 之内，单位km
        $lngR  = $range / cos($centorlatitude * pi()/ 180);
        $this->MaxLatitude= $centorlatitude + $range; //最大纬度
        $this->MinLatitude= $centorlatitude - $range; //最小纬度
        $this->MaxLongitude= $centorLogitude + $lngR; //最大经度
        $this->MinLongitude= $centorLogitude - $lngR; //最小经度
        //得出这四个值以后，就可以根据你数据库里存的经纬度信息查找记录了~
    }

    /**
     * 将角度换算为弧度。
     * @param float $degrees 角度
     * @return float 弧度
     * */
    private function ConvertDegreesToRadians($degrees)
    {
        return $degrees * M_PI / 180;
    }

    /**
     * 根据经纬度，计算2个点之间的距离。
     * @param float $lat1 纬度1
     * @param float $lon1 经度1
     * @param float $lat2 纬度2
     * @param float $lon2 经度2
     * @return float 距离（公里、千米）
     * */

    public function Distance($lat1, $lon1, $lat2, $lon2){
        //用haversine公式计算球面两点间的距离。
        //经纬度转换成弧度
        $lat1 = $this->ConvertDegreesToRadians($lat1);
        $lon1 = $this->ConvertDegreesToRadians($lon1);
        $lat2 = $this->ConvertDegreesToRadians($lat2);
        $lon2 = $this->ConvertDegreesToRadians($lon2);
        //差值
        $vLon = abs($lon1 - $lon2);
        $vLat = abs($lat1 - $lat2);
        //一个球体上的切面，它的圆心即是球心的一个周长最大的圆。
        $h = $this->HaverSin($vLat)+ cos($lat1)* cos($lat2)* $this->HaverSin($vLon);
        return 2 * self::EARTH_RADIUS * asin(sqrt($h));
    }

    private function HaverSin($theta)
    {
        $v = sin($theta / 2);
        return $v * $v;
    }

}