# GetMbr
根据经纬度,获取'MBR'数据

## 说明:
> 这个根据一个经纬度坐标、距离然后求另外一个经纬度坐标的作用，
> 主要就是确定一个最小外包矩形(Minimum bounding rectangle，简称MBR)。
>
> 这个矩形是包含5公里范围内所有这些有效信息的一个最小矩形。
> 利用公式，求出四个方向0度、90度、180度、270度方向上的四个坐标点就可以得到这个MBR。
> 
> 例如，我要找一个坐标点(lat,lon)的5公里范围内的所有商户信息、景点信息等。
> 这个MBR就是一个经纬度范围值，包含了最大经度，最小经度，最大纬度，最小纬度。
>
> 如果表里存有100万条数据，数据包含一个lat、lon的经纬度信息。
> 就可以先根据输入的经纬度和距离得到一个MBR，然后通过类似已下的SQL进行距离筛选了

## 使用示例
```
require_once './vendor/autoload.php';

use Hcg\GetMbr\GetMbr as GetMbr;

# 获取经纬度104.031252,30.710894，半径为10公里的'MBR'数据
# 返回数组，其中包含字段：
# MaxLatitude：最大经度
# MinLatitude：最小经度
# MaxLongitude：最大纬度
# MinLongitude：最大纬度
$gpsdis = (new Getmbr)->Main(104.031252,30.710894,10);

# 根据经纬度，计算2个点之间的距离。
# 返回距离（单位公里、千米）
$distance = (new Getmbr)->Distance(104.031252, 30.710894, 103.863918,30.447486);
```

示例程序输出：
```
# $gpsdis变量的结果
array(4) {
  ["MaxLatitude"]=>
  float(104.12115880154)
  ["MinLatitude"]=>
  float(103.94134519846)
  ["MaxLongitude"]=>
  float(30.34006953811)
  ["MinLongitude"]=>
  float(31.08171846189)
}
# $distance变量的结果
float(19.900953356431)
```
