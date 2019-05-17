/* PCAS (Province City Area Selector 省、市、地区联动选择JS封装类) Ver 2.02 完整版 *\

 制作时间:2005-12-30
 更新时间:2006-01-24
 数据修正:2012-01-17（截止2011年10月31日）

 演示地址:http://www.popub.net/script/pcasunzip.html
 下载地址:http://www.popub.net/script/pcasunzip.js
 应用说明:页面包含<script type="text/javascript" src="pcasunzip.js" charset="gb2312"></script>
 省市联动
 new PCAS("Province","City")
 new PCAS("Province","City","吉林省")
 new PCAS("Province","City","吉林省","吉林市")
 省市地区联动
 new PCAS("Province","City","Area")
 new PCAS("Province","City","Area","吉林省")
 new PCAS("Province","City","Area","吉林省","松原市")
 new PCAS("Province","City","Area","吉林省","松原市","宁江区")
 省、市、地区对象取得的值均为实际值。
 注：省、市、地区提示信息选项的值为""(空字符串)

 \*** 程序制作/版权所有:崔永祥(333) E-Mail:zhadan007@21cn.com 网址:http://www.popub.net ***/


SPT = "-省份-";
SCT = "-城市-";
SAT = "-地区-";
ShowT = 0; /* 提示文字 0:不显示 1:显示 */
PCAD = "北京市$北京市,东城区,西城区,朝阳区,丰台区,石景山区,海淀区,门头沟区,房山区,通州区,顺义区,昌平区,大兴区,怀柔区,平谷区,密云区,延庆区,其它区#天津市$天津市,和平区,河东区,河西区,南开区,河北区,红桥区,东丽区,西青区,津南区,北辰区,武清区,宝坻区,滨海新区,宁河区,静海区,蓟州区,其它区#河北省$石家庄市,长安区,桥西区,新华区,井陉矿区,裕华区,井陉县,正定县,栾城区,行唐县,灵寿县,高邑县,深泽县,赞皇县,无极县,平山县,元氏县,赵县,辛集市,藁城区,晋州市,新乐市,鹿泉区,其它区|唐山市,路南区,路北区,古冶区,开平区,丰南区,丰润区,滦县,滦南县,乐亭县,迁西县,玉田县,曹妃甸区,遵化市,迁安市,其它区|秦皇岛市,海港区,山海关区,北戴河区,青龙满族自治县,昌黎县,抚宁区,卢龙县,其它区|邯郸市,邯山区,丛台区,复兴区,峰峰矿区,临漳县,成安县,大名县,涉县,磁县,肥乡区,永年区,邱县,鸡泽县,广平县,馆陶县,魏县,曲周县,武安市,其它区|邢台市,桥东区,桥西区,邢台县,临城县,内丘县,柏乡县,隆尧县,任县,南和县,宁晋县,巨鹿县,新河县,广宗县,平乡县,威县,清河县,临西县,南宫市,沙河市,其它区|保定市,竞秀区,莲池区,满城区,清苑区,涞水县,阜平县,徐水区,定兴县,唐县,高阳县,容城县,涞源县,望都县,安新县,易县,曲阳县,蠡县,顺平县,博野县,雄县,涿州市,定州市,安国市,高碑店市,其它区|张家口市,桥东区,桥西区,宣化区,下花园区,张北县,康保县,沽源县,尚义县,蔚县,阳原县,怀安县,万全区,怀来县,涿鹿县,赤城县,崇礼区,其它区|承德市,双桥区,双滦区,鹰手营子矿区,承德县,兴隆县,平泉市,滦平县,隆化县,丰宁满族自治县,宽城满族自治县,围场满族蒙古族自治县,其它区|沧州市,新华区,运河区,沧县,青县,东光县,海兴县,盐山县,肃宁县,南皮县,吴桥县,献县,孟村回族自治县,泊头市,任丘市,黄骅市,河间市,其它区|廊坊市,安次区,广阳区,固安县,永清县,香河县,大城县,文安县,大厂回族自治县,霸州市,三河市,其它区|衡水市,桃城区,枣强县,武邑县,武强县,饶阳县,安平县,故城县,景县,阜城县,冀州区,深州市,其它区#山西省$太原市,小店区,迎泽区,杏花岭区,尖草坪区,万柏林区,晋源区,清徐县,阳曲县,娄烦县,古交市,其它区|大同市,城区,矿区,南郊区,新荣区,阳高县,天镇县,广灵县,灵丘县,浑源县,左云县,大同县,其它区|阳泉市,城区,矿区,郊区,平定县,盂县,其它区|长治市,长治县,襄垣县,屯留县,平顺县,黎城县,壶关县,长子县,武乡县,沁县,沁源县,潞城市,城区,郊区,其它区|晋城市,城区,沁水县,阳城县,陵川县,泽州县,高平市,其它区|朔州市,朔城区,平鲁区,山阴县,应县,右玉县,怀仁县,其它区|晋中市,榆次区,榆社县,左权县,和顺县,昔阳县,寿阳县,太谷县,祁县,平遥县,灵石县,介休市,其它区|运城市,盐湖区,临猗县,万荣县,闻喜县,稷山县,新绛县,绛县,垣曲县,夏县,平陆县,芮城县,永济市,河津市,其它区|忻州市,忻府区,定襄县,五台县,代县,繁峙县,宁武县,静乐县,神池县,五寨县,岢岚县,河曲县,保德县,偏关县,原平市,其它区|临汾市,尧都区,曲沃县,翼城县,襄汾县,洪洞县,古县,安泽县,浮山县,吉县,乡宁县,大宁县,隰县,永和县,蒲县,汾西县,侯马市,霍州市,其它区|吕梁市,离石区,文水县,交城县,兴县,临县,柳林县,石楼县,岚县,方山县,中阳县,交口县,孝义市,汾阳市,其它区#内蒙古自治区$呼和浩特市,新城区,回民区,玉泉区,赛罕区,土默特左旗,托克托县,和林格尔县,清水河县,武川县,其它区|包头市,东河区,昆都仑区,青山区,石拐区,白云鄂博矿区,九原区,土默特右旗,固阳县,达尔罕茂明安联合旗,其它区|乌海市,海勃湾区,海南区,乌达区,其它区|赤峰市,红山区,元宝山区,松山区,阿鲁科尔沁旗,巴林左旗,巴林右旗,林西县,克什克腾旗,翁牛特旗,喀喇沁旗,宁城县,敖汉旗,其它区|通辽市,科尔沁区,科尔沁左翼中旗,科尔沁左翼后旗,开鲁县,库伦旗,奈曼旗,扎鲁特旗,霍林郭勒市,其它区|鄂尔多斯市,东胜区,康巴什区,达拉特旗,准格尔旗,鄂托克前旗,鄂托克旗,杭锦旗,乌审旗,伊金霍洛旗,其它区|呼伦贝尔市,海拉尔区,扎赉诺尔区,阿荣旗,莫力达瓦达斡尔族自治旗,鄂伦春自治旗,鄂温克族自治旗,陈巴尔虎旗,新巴尔虎左旗,新巴尔虎右旗,满洲里市,牙克石市,扎兰屯市,额尔古纳市,根河市,其它区|巴彦淖尔市,临河区,五原县,磴口县,乌拉特前旗,乌拉特中旗,乌拉特后旗,杭锦后旗,其它区|乌兰察布市,集宁区,卓资县,化德县,商都县,兴和县,凉城县,察哈尔右翼前旗,察哈尔右翼中旗,察哈尔右翼后旗,四子王旗,丰镇市,其它区|兴安盟,乌兰浩特市,阿尔山市,科尔沁右翼前旗,科尔沁右翼中旗,扎赉特旗,突泉县,其它区|锡林郭勒盟,二连浩特市,锡林浩特市,阿巴嘎旗,苏尼特左旗,苏尼特右旗,东乌珠穆沁旗,西乌珠穆沁旗,太仆寺旗,镶黄旗,正镶白旗,正蓝旗,多伦县,其它区|阿拉善盟,阿拉善左旗,阿拉善右旗,额济纳旗,其它区#辽宁省$沈阳市,和平区,沈河区,大东区,皇姑区,铁西区,苏家屯区,浑南区,于洪区,辽中区,康平县,法库县,新民市,沈北新区,其它区|大连市,中山区,西岗区,沙河口区,甘井子区,旅顺口区,金州区,长海县,瓦房店市,普兰店区,庄河市,其它区|鞍山市,铁东区,铁西区,立山区,千山区,台安县,岫岩满族自治县,海城市,其它区|抚顺市,新抚区,东洲区,望花区,顺城区,抚顺县,新宾满族自治县,清原满族自治县,其它区|本溪市,平山区,溪湖区,明山区,南芬区,本溪满族自治县,桓仁满族自治县,其它区|丹东市,元宝区,振兴区,振安区,宽甸满族自治县,东港市,凤城市,其它区|锦州市,古塔区,凌河区,太和区,黑山县,义县,凌海市,北镇市,其它区|营口市,站前区,西市区,鲅鱼圈区,老边区,盖州市,大石桥市,其它区|阜新市,海州区,新邱区,太平区,清河门区,细河区,阜新蒙古族自治县,彰武县,其它区|辽阳市,白塔区,文圣区,宏伟区,弓长岭区,太子河区,辽阳县,灯塔市,其它区|盘锦市,双台子区,兴隆台区,大洼区,盘山县,其它区|铁岭市,银州区,清河区,铁岭县,西丰县,昌图县,调兵山市,开原市,其它区|朝阳市,双塔区,龙城区,朝阳县,建平县,喀喇沁左翼蒙古族自治县,北票市,凌源市,其它区|葫芦岛市,连山区,龙港区,南票区,绥中县,建昌县,兴城市,其它区#吉林省$长春市,南关区,宽城区,朝阳区,二道区,绿园区,双阳区,农安县,九台区,榆树市,德惠市,其它区|吉林市,昌邑区,龙潭区,船营区,丰满区,永吉县,蛟河市,桦甸市,舒兰市,磐石市,其它区|四平市,铁西区,铁东区,梨树县,伊通满族自治县,公主岭市,双辽市,其它区|辽源市,龙山区,西安区,东丰县,东辽县,其它区|通化市,东昌区,二道江区,通化县,辉南县,柳河县,梅河口市,集安市,其它区|白山市,浑江区,抚松县,靖宇县,长白朝鲜族自治县,江源区,临江市,其它区|松原市,宁江区,前郭尔罗斯蒙古族自治县,长岭县,乾安县,扶余市,其它区|白城市,洮北区,镇赉县,通榆县,洮南市,大安市,其它区|延边朝鲜族自治州,延吉市,图们市,敦化市,珲春市,龙井市,和龙市,汪清县,安图县,其它区#黑龙江省$哈尔滨市,道里区,南岗区,道外区,香坊区,平房区,松北区,呼兰区,依兰县,方正县,宾县,巴彦县,木兰县,通河县,延寿县,阿城区,双城区,尚志市,五常市,其它区|齐齐哈尔市,龙沙区,建华区,铁锋区,昂昂溪区,富拉尔基区,碾子山区,梅里斯达斡尔族区,龙江县,依安县,泰来县,甘南县,富裕县,克山县,克东县,拜泉县,讷河市,其它区|鸡西市,鸡冠区,恒山区,滴道区,梨树区,城子河区,麻山区,鸡东县,虎林市,密山市,其它区|鹤岗市,向阳区,工农区,南山区,兴安区,东山区,兴山区,萝北县,绥滨县,其它区|双鸭山市,尖山区,岭东区,四方台区,宝山区,集贤县,友谊县,宝清县,饶河县,其它区|大庆市,萨尔图区,龙凤区,让胡路区,红岗区,大同区,肇州县,肇源县,林甸县,杜尔伯特蒙古族自治县,其它区|伊春市,伊春区,南岔区,友好区,西林区,翠峦区,新青区,美溪区,金山屯区,五营区,乌马河区,汤旺河区,带岭区,乌伊岭区,红星区,上甘岭区,嘉荫县,铁力市,其它区|佳木斯市,向阳区,前进区,东风区,郊区,桦南县,桦川县,汤原县,抚远市,同江市,富锦市,其它区|七台河市,新兴区,桃山区,茄子河区,勃利县,其它区|牡丹江市,东安区,阳明区,爱民区,西安区,东宁市,林口县,绥芬河市,海林市,宁安市,穆棱市,其它区|黑河市,爱辉区,嫩江县,逊克县,孙吴县,北安市,五大连池市,其它区|绥化市,北林区,望奎县,兰西县,青冈县,庆安县,明水县,绥棱县,安达市,肇东市,海伦市,其它区|大兴安岭地区,呼玛县,塔河县,漠河县,加格达奇区,其它区#上海市$上海市,黄浦区,徐汇区,长宁区,静安区,普陀区,虹口区,杨浦区,闵行区,宝山区,嘉定区,浦东新区,金山区,松江区,青浦区,奉贤区,崇明区,其它区#江苏省$南京市,玄武区,秦淮区,建邺区,鼓楼区,浦口区,栖霞区,雨花台区,江宁区,六合区,溧水区,高淳区,其它区|无锡市,锡山区,惠山区,滨湖区,梁溪区,新吴区,江阴市,宜兴市,其它区|徐州市,鼓楼区,云龙区,贾汪区,泉山区,丰县,沛县,铜山区,睢宁县,新沂市,邳州市,其它区|常州市,天宁区,钟楼区,新北区,武进区,溧阳市,金坛区,其它区|苏州市,虎丘区,吴中区,相城区,姑苏区,常熟市,张家港市,昆山市,吴江区,太仓市,苏州工业园区,其它区|南通市,崇川区,港闸区,通州区,海安县,如东县,启东市,如皋市,海门市,其它区|连云港市,连云区,海州区,赣榆区,东海县,灌云县,灌南县,其它区|淮安市,清江浦区,淮安区,淮阴区,涟水县,洪泽区,盱眙县,金湖县,其它区|盐城市,亭湖区,盐都区,响水县,滨海县,阜宁县,射阳县,建湖县,东台市,大丰区,其它区|扬州市,广陵区,邗江区,宝应县,仪征市,高邮市,江都区,其它区|镇江市,京口区,润州区,丹徒区,丹阳市,扬中市,句容市,其它区|泰州市,海陵区,高港区,兴化市,靖江市,泰兴市,姜堰区,其它区|宿迁市,宿城区,宿豫区,沭阳县,泗阳县,泗洪县,其它区#浙江省$杭州市,上城区,下城区,江干区,拱墅区,西湖区,滨江区,萧山区,余杭区,桐庐县,淳安县,建德市,富阳区,临安区,其它区|宁波市,海曙区,江北区,北仑区,镇海区,鄞州区,象山县,宁海县,余姚市,慈溪市,奉化区,其它区|温州市,鹿城区,龙湾区,瓯海区,洞头区,永嘉县,平阳县,苍南县,文成县,泰顺县,瑞安市,乐清市,其它区|嘉兴市,南湖区,秀洲区,嘉善县,海盐县,海宁市,平湖市,桐乡市,其它区|湖州市,吴兴区,南浔区,德清县,长兴县,安吉县,其它区|绍兴市,越城区,柯桥区,新昌县,诸暨市,上虞区,嵊州市,其它区|金华市,婺城区,金东区,武义县,浦江县,磐安县,兰溪市,义乌市,东阳市,永康市,其它区|衢州市,柯城区,衢江区,常山县,开化县,龙游县,江山市,其它区|舟山市,定海区,普陀区,岱山县,嵊泗县,其它区|台州市,椒江区,黄岩区,路桥区,玉环市,三门县,天台县,仙居县,温岭市,临海市,其它区|丽水市,莲都区,青田县,缙云县,遂昌县,松阳县,云和县,庆元县,景宁畲族自治县,龙泉市,其它区#安徽省$合肥市,瑶海区,庐阳区,蜀山区,包河区,长丰县,肥东县,肥西县,其它区,巢湖市,庐江县|芜湖市,镜湖区,弋江区,鸠江区,三山区,芜湖县,繁昌县,南陵县,其它区,无为县|蚌埠市,龙子湖区,蚌山区,禹会区,淮上区,怀远县,五河县,固镇县,其它区|淮南市,大通区,田家庵区,谢家集区,八公山区,潘集区,凤台县,其它区,寿县|马鞍山市,花山区,雨山区,博望区,当涂县,其它区,含山县,和县|淮北市,杜集区,相山区,烈山区,濉溪县,其它区|铜陵市,铜官区,郊区,义安区,其它区,枞阳县|安庆市,迎江区,大观区,宜秀区,怀宁县,潜山县,太湖县,宿松县,望江县,岳西县,桐城市,其它区|黄山市,屯溪区,黄山区,徽州区,歙县,休宁县,黟县,祁门县,其它区|滁州市,琅琊区,南谯区,来安县,全椒县,定远县,凤阳县,天长市,明光市,其它区|阜阳市,颍州区,颍东区,颍泉区,临泉县,太和县,阜南县,颍上县,界首市,其它区|宿州市,埇桥区,砀山县,萧县,灵璧县,泗县,其它区|六安市,金安区,裕安区,叶集区,霍邱县,舒城县,金寨县,霍山县,其它区|亳州市,谯城区,涡阳县,蒙城县,利辛县,其它区|池州市,贵池区,东至县,石台县,青阳县,其它区|宣城市,宣州区,郎溪县,广德县,泾县,绩溪县,旌德县,宁国市,其它区#福建省$福州市,鼓楼区,台江区,仓山区,马尾区,晋安区,闽侯县,连江县,罗源县,闽清县,永泰县,平潭县,福清市,长乐区,其它区|厦门市,思明区,海沧区,湖里区,集美区,同安区,翔安区,其它区|莆田市,城厢区,涵江区,荔城区,秀屿区,仙游县,其它区|三明市,梅列区,三元区,明溪县,清流县,宁化县,大田县,尤溪县,沙县,将乐县,泰宁县,建宁县,永安市,其它区|泉州市,鲤城区,丰泽区,洛江区,泉港区,惠安县,安溪县,永春县,德化县,金门县,石狮市,晋江市,南安市,其它区|漳州市,芗城区,龙文区,云霄县,漳浦县,诏安县,长泰县,东山县,南靖县,平和县,华安县,龙海市,其它区|南平市,延平区,顺昌县,浦城县,光泽县,松溪县,政和县,邵武市,武夷山市,建瓯市,建阳区,其它区|龙岩市,新罗区,长汀县,永定区,上杭县,武平县,连城县,漳平市,其它区|宁德市,蕉城区,霞浦县,古田县,屏南县,寿宁县,周宁县,柘荣县,福安市,福鼎市,其它区#江西省$南昌市,东湖区,西湖区,青云谱区,湾里区,青山湖区,南昌县,新建区,安义县,进贤县,其它区|景德镇市,昌江区,珠山区,浮梁县,乐平市,其它区|萍乡市,安源区,湘东区,莲花县,上栗县,芦溪县,其它区|九江市,濂溪区,浔阳区,柴桑区,武宁县,修水县,永修县,德安县,庐山市,都昌县,湖口县,彭泽县,瑞昌市,其它区,共青城市|新余市,渝水区,分宜县,其它区|鹰潭市,月湖区,余江县,贵溪市,其它区|赣州市,章贡区,赣县区,信丰县,大余县,上犹县,崇义县,安远县,龙南县,定南县,全南县,宁都县,于都县,兴国县,会昌县,寻乌县,石城县,瑞金市,南康区,其它区|吉安市,吉州区,青原区,吉安县,吉水县,峡江县,新干县,永丰县,泰和县,遂川县,万安县,安福县,永新县,井冈山市,其它区|宜春市,袁州区,奉新县,万载县,上高县,宜丰县,靖安县,铜鼓县,丰城市,樟树市,高安市,其它区|抚州市,临川区,南城县,黎川县,南丰县,崇仁县,乐安县,宜黄县,金溪县,资溪县,东乡区,广昌县,其它区|上饶市,信州区,广信区,广丰区,玉山县,铅山县,横峰县,弋阳县,余干县,鄱阳县,万年县,婺源县,德兴市,其它区#山东省$济南市,历下区,市中区,槐荫区,天桥区,历城区,长清区,平阴县,济阳县,商河县,章丘区,其它区|青岛市,市南区,市北区,黄岛区,崂山区,李沧区,城阳区,胶州市,即墨区,平度市,莱西市,其它区|淄博市,淄川区,张店区,博山区,临淄区,周村区,桓台县,高青县,沂源县,其它区|枣庄市,市中区,薛城区,峄城区,台儿庄区,山亭区,滕州市,其它区|东营市,东营区,河口区,垦利区,利津县,广饶县,其它区|烟台市,芝罘区,福山区,牟平区,莱山区,长岛县,龙口市,莱阳市,莱州市,蓬莱市,招远市,栖霞市,海阳市,其它区|潍坊市,潍城区,寒亭区,坊子区,奎文区,临朐县,昌乐县,青州市,诸城市,寿光市,安丘市,高密市,昌邑市,其它区|济宁市,任城区,微山县,鱼台县,金乡县,嘉祥县,汶上县,泗水县,梁山县,曲阜市,兖州区,邹城市,其它区|泰安市,泰山区,岱岳区,宁阳县,东平县,新泰市,肥城市,其它区|威海市,环翠区,文登区,荣成市,乳山市,其它区|日照市,东港区,岚山区,五莲县,莒县,其它区|莱芜市,莱城区,钢城区,其它区|临沂市,兰山区,罗庄区,河东区,沂南县,郯城县,沂水县,兰陵县,费县,平邑县,莒南县,蒙阴县,临沭县,其它区|德州市,德城区,陵城区,宁津县,庆云县,临邑县,齐河县,平原县,夏津县,武城县,乐陵市,禹城市,其它区|聊城市,东昌府区,阳谷县,莘县,茌平县,东阿县,冠县,高唐县,临清市,其它区|滨州市,滨城区,惠民县,阳信县,无棣县,沾化区,博兴县,邹平县,其它区|菏泽市,牡丹区,曹县,单县,成武县,巨野县,郓城县,鄄城县,定陶区,东明县,其它区#河南省$郑州市,中原区,二七区,管城回族区,金水区,上街区,惠济区,中牟县,巩义市,荥阳市,新密市,新郑市,登封市,其它区|开封市,龙亭区,顺河回族区,鼓楼区,禹王台区,杞县,通许县,尉氏县,祥符区,兰考县,其它区|洛阳市,老城区,西工区,瀍河回族区,涧西区,吉利区,洛龙区,孟津县,新安县,栾川县,嵩县,汝阳县,宜阳县,洛宁县,伊川县,偃师市,其它区|平顶山市,新华区,卫东区,石龙区,湛河区,宝丰县,叶县,鲁山县,郏县,舞钢市,汝州市,其它区|安阳市,文峰区,北关区,殷都区,龙安区,安阳县,汤阴县,滑县,内黄县,林州市,其它区|鹤壁市,鹤山区,山城区,淇滨区,浚县,淇县,其它区|新乡市,红旗区,卫滨区,凤泉区,牧野区,新乡县,获嘉县,原阳县,延津县,封丘县,长垣县,卫辉市,辉县市,其它区|焦作市,解放区,中站区,马村区,山阳区,修武县,博爱县,武陟县,温县,沁阳市,孟州市,其它区|濮阳市,华龙区,清丰县,南乐县,范县,台前县,濮阳县,其它区|许昌市,魏都区,建安区,鄢陵县,襄城县,禹州市,长葛市,其它区|漯河市,源汇区,郾城区,召陵区,舞阳县,临颍县,其它区|三门峡市,湖滨区,渑池县,陕州区,卢氏县,义马市,灵宝市,其它区|南阳市,宛城区,卧龙区,南召县,方城县,西峡县,镇平县,内乡县,淅川县,社旗县,唐河县,新野县,桐柏县,邓州市,其它区|商丘市,梁园区,睢阳区,民权县,睢县,宁陵县,柘城县,虞城县,夏邑县,永城市,其它区|信阳市,浉河区,平桥区,罗山县,光山县,新县,商城县,固始县,潢川县,淮滨县,息县,其它区|周口市,川汇区,扶沟县,西华县,商水县,沈丘县,郸城县,淮阳县,太康县,鹿邑县,项城市,其它区|驻马店市,驿城区,西平县,上蔡县,平舆县,正阳县,确山县,泌阳县,汝南县,遂平县,新蔡县,其它区|济源市,沁园街道,济水街道,北海街道,天坛街道,玉泉街道,克井镇,五龙口镇,轵城镇,承留镇,邵原镇,坡头镇,梨林镇,大峪镇,思礼镇,王屋镇,下冶镇#湖北省$武汉市,江岸区,江汉区,硚口区,汉阳区,武昌区,青山区,洪山区,东西湖区,汉南区,蔡甸区,江夏区,黄陂区,新洲区,其它区|黄石市,黄石港区,西塞山区,下陆区,铁山区,阳新县,大冶市,其它区|十堰市,茅箭区,张湾区,郧阳区,郧西县,竹山县,竹溪县,房县,丹江口市,其它区|宜昌市,西陵区,伍家岗区,点军区,猇亭区,夷陵区,远安县,兴山县,秭归县,长阳土家族自治县,五峰土家族自治县,宜都市,当阳市,枝江市,其它区|襄阳市,襄城区,樊城区,襄州区,南漳县,谷城县,保康县,老河口市,枣阳市,宜城市,其它区|鄂州市,梁子湖区,华容区,鄂城区,其它区|荆门市,东宝区,掇刀区,京山县,沙洋县,钟祥市,其它区|孝感市,孝南区,孝昌县,大悟县,云梦县,应城市,安陆市,汉川市,其它区|荆州市,沙市区,荆州区,公安县,监利县,江陵县,石首市,洪湖市,松滋市,其它区|黄冈市,黄州区,团风县,红安县,罗田县,英山县,浠水县,蕲春县,黄梅县,麻城市,武穴市,其它区|咸宁市,咸安区,嘉鱼县,通城县,崇阳县,通山县,赤壁市,其它区|随州市,曾都区,随县,广水市,其它区|恩施土家族苗族自治州,恩施市,利川市,建始县,巴东县,宣恩县,咸丰县,来凤县,鹤峰县,其它区|仙桃市,沙嘴街道,干河街道,龙华山街道,郑场镇,毛嘴镇,豆河镇,三伏潭镇,胡场镇,长倘口镇,西流河镇,沙湖镇,杨林尾镇,彭场镇,张沟镇,郭河镇,沔城回族镇,通海口镇,陈场镇,工业园区,九合垸原种场,沙湖原种场,五湖渔场,赵西垸林场,畜禽良种场,排湖风景区|潜江市,园林街道,杨市街道,周矶街道,广华街道,泰丰街道,高场街道,竹根滩镇,渔洋镇,王场镇,高石碑镇,熊口镇,老新镇,浩口镇,积玉口镇,张金镇,龙湾镇,江汉石油管理局,潜江经济开发区,周矶管理区,后湖管理区,熊口管理区,总口管理区,白鹭湖管理区,运粮湖管理区,浩口原种场|天门市,竟陵街道,侨乡街道开发区,杨林街道,多宝镇,拖市镇,张港镇,蒋场镇,汪场镇,渔薪镇,黄潭镇,岳口镇,横林镇,彭市镇,麻洋镇,多祥镇,干驿镇,马湾镇,卢市镇,小板镇,九真镇,皂市镇,胡市镇,石河镇,佛子山镇,净潭乡,蒋湖农场,白茅湖农场,沉湖管委会|神农架林区,松柏镇,阳日镇,木鱼镇,红坪镇,新华镇,九湖镇,宋洛乡,下谷坪土家族乡#湖南省$长沙市,芙蓉区,天心区,岳麓区,开福区,雨花区,长沙县,望城区,宁乡市,浏阳市,其它区|株洲市,荷塘区,芦淞区,石峰区,天元区,株洲县,攸县,茶陵县,炎陵县,醴陵市,其它区|湘潭市,雨湖区,岳塘区,湘潭县,湘乡市,韶山市,其它区|衡阳市,珠晖区,雁峰区,石鼓区,蒸湘区,南岳区,衡阳县,衡南县,衡山县,衡东县,祁东县,耒阳市,常宁市,其它区|邵阳市,双清区,大祥区,北塔区,邵东县,新邵县,邵阳县,隆回县,洞口县,绥宁县,新宁县,城步苗族自治县,武冈市,其它区|岳阳市,岳阳楼区,云溪区,君山区,岳阳县,华容县,湘阴县,平江县,汨罗市,临湘市,其它区|常德市,武陵区,鼎城区,安乡县,汉寿县,澧县,临澧县,桃源县,石门县,津市市,其它区|张家界市,永定区,武陵源区,慈利县,桑植县,其它区|益阳市,资阳区,赫山区,南县,桃江县,安化县,沅江市,其它区|郴州市,北湖区,苏仙区,桂阳县,宜章县,永兴县,嘉禾县,临武县,汝城县,桂东县,安仁县,资兴市,其它区|永州市,零陵区,冷水滩区,祁阳县,东安县,双牌县,道县,江永县,宁远县,蓝山县,新田县,江华瑶族自治县,其它区|怀化市,鹤城区,中方县,沅陵县,辰溪县,溆浦县,会同县,麻阳苗族自治县,新晃侗族自治县,芷江侗族自治县,靖州苗族侗族自治县,通道侗族自治县,洪江市,其它区|娄底市,娄星区,双峰县,新化县,冷水江市,涟源市,其它区|湘西土家族苗族自治州,吉首市,泸溪县,凤凰县,花垣县,保靖县,古丈县,永顺县,龙山县,其它区#广东省$广州市,荔湾区,越秀区,海珠区,天河区,白云区,黄埔区,番禺区,花都区,南沙区,增城区,从化区,其它区|韶关市,武江区,浈江区,曲江区,始兴县,仁化县,翁源县,乳源瑶族自治县,新丰县,乐昌市,南雄市,其它区|深圳市,罗湖区,福田区,南山区,宝安区,龙岗区,盐田区,其它区,坪山区,龙华区|珠海市,香洲区,斗门区,金湾区,其它区|汕头市,龙湖区,金平区,濠江区,潮阳区,潮南区,澄海区,南澳县,其它区|佛山市,禅城区,南海区,顺德区,三水区,高明区,其它区|江门市,蓬江区,江海区,新会区,台山市,开平市,鹤山市,恩平市,其它区|湛江市,赤坎区,霞山区,坡头区,麻章区,遂溪县,徐闻县,廉江市,雷州市,吴川市,其它区|茂名市,茂南区,电白区,高州市,化州市,信宜市,其它区|肇庆市,端州区,鼎湖区,广宁县,怀集县,封开县,德庆县,高要区,四会市,其它区|惠州市,惠城区,惠阳区,博罗县,惠东县,龙门县,其它区|梅州市,梅江区,梅县区,大埔县,丰顺县,五华县,平远县,蕉岭县,兴宁市,其它区|汕尾市,城区,海丰县,陆河县,陆丰市,其它区|河源市,源城区,紫金县,龙川县,连平县,和平县,东源县,其它区|阳江市,江城区,阳西县,阳东区,阳春市,其它区|清远市,清城区,佛冈县,阳山县,连山壮族瑶族自治县,连南瑶族自治县,清新区,英德市,连州市,其它区|东莞市,东城街道,南城街道,万江街道,莞城街道,石碣镇,石龙镇,茶山镇,石排镇,企石镇,横沥镇,桥头镇,谢岗镇,东坑镇,常平镇,寮步镇,樟木头镇,大朗镇,黄江镇,清溪镇,塘厦镇,凤岗镇,大岭山镇,长安镇,虎门镇,厚街镇,沙田镇,道滘镇,洪梅镇,麻涌镇,望牛墩镇,中堂镇,高埗镇,松山湖管委会,虎门港管委会,东莞生态园|中山市,石岐区街道,东区街道,火炬开发区街道,西区街道,南区街道,五桂山街道,小榄镇,黄圃镇,民众镇,东凤镇,东升镇,古镇镇,沙溪镇,坦洲镇,港口镇,三角镇,横栏镇,南头镇,阜沙镇,南朗镇,三乡镇,板芙镇,大涌镇,神湾镇|东沙群岛,东沙群岛|潮州市,湘桥区,潮安区,饶平县,其它区|揭阳市,榕城区,揭东区,揭西县,惠来县,普宁市,其它区|云浮市,云城区,新兴县,郁南县,云安区,罗定市,其它区#广西壮族自治区$南宁市,兴宁区,青秀区,江南区,西乡塘区,良庆区,邕宁区,武鸣区,隆安县,马山县,上林县,宾阳县,横县,其它区|柳州市,城中区,鱼峰区,柳南区,柳北区,柳江区,柳城县,鹿寨县,融安县,融水苗族自治县,三江侗族自治县,其它区|桂林市,秀峰区,叠彩区,象山区,七星区,雁山区,阳朔县,临桂区,灵川县,全州县,兴安县,永福县,灌阳县,龙胜各族自治县,资源县,平乐县,荔浦县,恭城瑶族自治县,其它区|梧州市,万秀区,长洲区,龙圩区,苍梧县,藤县,蒙山县,岑溪市,其它区|北海市,海城区,银海区,铁山港区,合浦县,其它区|防城港市,港口区,防城区,上思县,东兴市,其它区|钦州市,钦南区,钦北区,灵山县,浦北县,其它区|贵港市,港北区,港南区,覃塘区,平南县,桂平市,其它区|玉林市,玉州区,福绵区,容县,陆川县,博白县,兴业县,北流市,其它区|百色市,右江区,田阳县,田东县,平果县,德保县,靖西市,那坡县,凌云县,乐业县,田林县,西林县,隆林各族自治县,其它区|贺州市,八步区,平桂区,昭平县,钟山县,富川瑶族自治县,其它区|河池市,金城江区,南丹县,天峨县,凤山县,东兰县,罗城仫佬族自治县,环江毛南族自治县,巴马瑶族自治县,都安瑶族自治县,大化瑶族自治县,宜州区,其它区|来宾市,兴宾区,忻城县,象州县,武宣县,金秀瑶族自治县,合山市,其它区|崇左市,江州区,扶绥县,宁明县,龙州县,大新县,天等县,凭祥市,其它区#海南省$海口市,秀英区,龙华区,琼山区,美兰区,其它区|三亚市,海棠区,吉阳区,天涯区,崖州区,其它区|三沙市,西沙群岛,南沙群岛,中沙群岛的岛礁及其海域|五指山市,通什镇,南圣镇,毛阳镇,番阳镇,畅好乡,毛道乡,水满乡,国营畅好农场|琼海市,嘉积镇,万泉镇,石壁镇,中原镇,博鳌镇,阳江镇,龙江镇,潭门镇,塔洋镇,长坡镇,大路镇,会山镇,国营东太农场,国营东红农场,国营东升农场,彬村山华侨农场|儋州市,那大镇,和庆镇,南丰镇,大成镇,雅星镇,兰洋镇,光村镇,木棠镇,海头镇,峨蔓镇,三都镇,王五镇,白马井镇,中和镇,排浦镇,东成镇,新州镇,国营西培农场,国营西联农场,国营蓝洋农场,国营八一农场,洋浦经济开发区,华南热作学院|文昌市,文城镇,重兴镇,蓬莱镇,会文镇,东路镇,潭牛镇,东阁镇,文教镇,东郊镇,龙楼镇,昌洒镇,翁田镇,抱罗镇,冯坡镇,锦山镇,铺前镇,公坡镇,国营东路农场,国营南阳农场,国营罗豆农场|万宁市,万城镇,龙滚镇,和乐镇,后安镇,大茂镇,东澳镇,礼纪镇,长丰镇,山根镇,北大镇,南桥镇,三更罗镇,国营东兴农场,国营东和农场,国营新中农场,兴隆华侨农场,地方国营六连林场|东方市,八所镇,东河镇,大田镇,感城镇,板桥镇,三家镇,四更镇,新龙镇,天安乡,江边乡,国营广坝农场,东方华侨农场|定安县,定城镇,新竹镇,龙湖镇,黄竹镇,雷鸣镇,龙门镇,龙河镇,岭口镇,翰林镇,富文镇,国营中瑞农场,国营南海农场,国营金鸡岭农场|屯昌县,屯城镇,新兴镇,枫木镇,乌坡镇,南吕镇,南坤镇,坡心镇,西昌镇,国营中建农场,国营中坤农场|澄迈县,金江镇,老城镇,瑞溪镇,永发镇,加乐镇,文儒镇,中兴镇,仁兴镇,福山镇,桥头镇,大丰镇,国营红光农场,国营红岗农场,国营西达农场,国营昆仑农场,国营和岭农场,国营金安农场|临高县,临城镇,波莲镇,东英镇,博厚镇,皇桐镇,多文镇,和舍镇,南宝镇,新盈镇,调楼镇,国营红华农场,国营加来农场|白沙黎族自治县,牙叉镇,七坊镇,邦溪镇,打安镇,细水乡,元门乡,南开乡,阜龙乡,青松乡,金波乡,荣邦乡,国营白沙农场,国营龙江农场,国营邦溪农场|昌江黎族自治县,石碌镇,叉河镇,十月田镇,乌烈镇,昌化镇,海尾镇,七叉镇,王下乡,国营红林农场,国营霸王岭林场,海南矿业联合有限公司|乐东黎族自治县,抱由镇,万冲镇,大安镇,志仲镇,千家镇,九所镇,利国镇,黄流镇,佛罗镇,尖峰镇,莺歌海镇,国营山荣农场,国营乐光农场,国营保国农场,国营尖峰岭林业公司,国营莺歌海盐场|陵水黎族自治县,椰林镇,光坡镇,三才镇,英州镇,隆广镇,文罗镇,本号镇,新村镇,黎安镇,提蒙乡,群英乡,国营岭门农场,国营南平农场,国营吊罗山林业公司|保亭黎族苗族自治县,保城镇,什玲镇,加茂镇,响水镇,新政镇,三道镇,六弓乡,南林乡,毛感乡,国营新星农场,海南保亭热带作物研究所,国营金江农场,国营三道农场|琼中黎族苗族自治县,营根镇,湾岭镇,黎母山镇,和平镇,长征镇,红毛镇,中平镇,吊罗山乡,上安乡,什运乡,国营阳江农场,国营乌石农场,国营加钗农场,国营长征农场,国营黎母山林业公司#重庆市$重庆市,万州区,涪陵区,渝中区,大渡口区,江北区,沙坪坝区,九龙坡区,南岸区,北碚区,渝北区,巴南区,黔江区,长寿区,綦江区,潼南区,铜梁区,大足区,荣昌区,璧山区,梁平区,城口县,丰都县,垫江县,武隆区,忠县,开州区,云阳县,奉节县,巫山县,巫溪县,石柱土家族自治县,秀山土家族苗族自治县,酉阳土家族苗族自治县,彭水苗族土家族自治县,江津区,合川区,永川区,南川区,其它区#四川省$成都市,锦江区,青羊区,金牛区,武侯区,成华区,龙泉驿区,青白江区,新都区,温江区,金堂县,双流区,郫都区,大邑县,蒲江县,新津县,都江堰市,彭州市,邛崃市,崇州市,其它区,简阳市|自贡市,自流井区,贡井区,大安区,沿滩区,荣县,富顺县,其它区|攀枝花市,东区,西区,仁和区,米易县,盐边县,其它区|泸州市,江阳区,纳溪区,龙马潭区,泸县,合江县,叙永县,古蔺县,其它区|德阳市,旌阳区,中江县,罗江区,广汉市,什邡市,绵竹市,其它区|绵阳市,涪城区,游仙区,三台县,盐亭县,安州区,梓潼县,北川羌族自治县,平武县,江油市,其它区|广元市,利州区,昭化区,朝天区,旺苍县,青川县,剑阁县,苍溪县,其它区|遂宁市,船山区,安居区,蓬溪县,射洪县,大英县,其它区|内江市,市中区,东兴区,威远县,资中县,隆昌市,其它区|乐山市,市中区,沙湾区,五通桥区,金口河区,犍为县,井研县,夹江县,沐川县,峨边彝族自治县,马边彝族自治县,峨眉山市,其它区|南充市,顺庆区,高坪区,嘉陵区,南部县,营山县,蓬安县,仪陇县,西充县,阆中市,其它区|眉山市,东坡区,仁寿县,彭山区,洪雅县,丹棱县,青神县,其它区|宜宾市,翠屏区,宜宾县,南溪区,江安县,长宁县,高县,珙县,筠连县,兴文县,屏山县,其它区|广安市,广安区,前锋区,岳池县,武胜县,邻水县,华蓥市,其它区|达州市,通川区,达川区,宣汉县,开江县,大竹县,渠县,万源市,其它区|雅安市,雨城区,名山区,荥经县,汉源县,石棉县,天全县,芦山县,宝兴县,其它区|巴中市,巴州区,恩阳区,通江县,南江县,平昌县,其它区|资阳市,雁江区,安岳县,乐至县,其它区|阿坝藏族羌族自治州,汶川县,理县,茂县,松潘县,九寨沟县,金川县,小金县,黑水县,马尔康市,壤塘县,阿坝县,若尔盖县,红原县,其它区|甘孜藏族自治州,康定市,泸定县,丹巴县,九龙县,雅江县,道孚县,炉霍县,甘孜县,新龙县,德格县,白玉县,石渠县,色达县,理塘县,巴塘县,乡城县,稻城县,得荣县,其它区|凉山彝族自治州,西昌市,木里藏族自治县,盐源县,德昌县,会理县,会东县,宁南县,普格县,布拖县,金阳县,昭觉县,喜德县,冕宁县,越西县,甘洛县,美姑县,雷波县,其它区#贵州省$贵阳市,南明区,云岩区,花溪区,乌当区,白云区,观山湖区,开阳县,息烽县,修文县,清镇市,其它区|六盘水市,钟山区,六枝特区,水城县,盘县,其它区|遵义市,红花岗区,汇川区,遵义县,桐梓县,绥阳县,正安县,道真仡佬族苗族自治县,务川仡佬族苗族自治县,凤冈县,湄潭县,余庆县,习水县,赤水市,仁怀市,其它区|安顺市,西秀区,平坝区,普定县,镇宁布依族苗族自治县,关岭布依族苗族自治县,紫云苗族布依族自治县,其它区|毕节市,七星关区,大方县,黔西县,金沙县,织金县,纳雍县,威宁彝族回族苗族自治县,赫章县,其它区|铜仁市,碧江区,万山区,江口县,玉屏侗族自治县,石阡县,思南县,印江土家族苗族自治县,德江县,沿河土家族自治县,松桃苗族自治县,其它区|黔西南布依族苗族自治州,兴义市,兴仁县,普安县,晴隆县,贞丰县,望谟县,册亨县,安龙县,其它区|黔东南苗族侗族自治州,凯里市,黄平县,施秉县,三穗县,镇远县,岑巩县,天柱县,锦屏县,剑河县,台江县,黎平县,榕江县,从江县,雷山县,麻江县,丹寨县,其它区|黔南布依族苗族自治州,都匀市,福泉市,荔波县,贵定县,瓮安县,独山县,平塘县,罗甸县,长顺县,龙里县,惠水县,三都水族自治县,其它区#云南省$昆明市,五华区,盘龙区,官渡区,西山区,东川区,呈贡区,晋宁县,富民县,宜良县,石林彝族自治县,嵩明县,禄劝彝族苗族自治县,寻甸回族彝族自治县,安宁市,其它区|曲靖市,麒麟区,马龙县,陆良县,师宗县,罗平县,富源县,会泽县,沾益县,宣威市,其它区|玉溪市,红塔区,江川区,澄江县,通海县,华宁县,易门县,峨山彝族自治县,新平彝族傣族自治县,元江哈尼族彝族傣族自治县,其它区|保山市,隆阳区,施甸县,龙陵县,昌宁县,腾冲市,其它区|昭通市,昭阳区,鲁甸县,巧家县,盐津县,大关县,永善县,绥江县,镇雄县,彝良县,威信县,水富县,其它区|丽江市,古城区,玉龙纳西族自治县,永胜县,华坪县,宁蒗彝族自治县,其它区|普洱市,思茅区,宁洱哈尼族彝族自治县,墨江哈尼族自治县,景东彝族自治县,景谷傣族彝族自治县,镇沅彝族哈尼族拉祜族自治县,江城哈尼族彝族自治县,孟连傣族拉祜族佤族自治县,澜沧拉祜族自治县,西盟佤族自治县,其它区|临沧市,临翔区,凤庆县,云县,永德县,镇康县,双江拉祜族佤族布朗族傣族自治县,耿马傣族佤族自治县,沧源佤族自治县,其它区|楚雄彝族自治州,楚雄市,双柏县,牟定县,南华县,姚安县,大姚县,永仁县,元谋县,武定县,禄丰县,其它区|红河哈尼族彝族自治州,个旧市,开远市,蒙自市,弥勒市,屏边苗族自治县,建水县,石屏县,泸西县,元阳县,红河县,金平苗族瑶族傣族自治县,绿春县,河口瑶族自治县,其它区|文山壮族苗族自治州,文山市,砚山县,西畴县,麻栗坡县,马关县,丘北县,广南县,富宁县,其它区|西双版纳傣族自治州,景洪市,勐海县,勐腊县,其它区|大理白族自治州,大理市,漾濞彝族自治县,祥云县,宾川县,弥渡县,南涧彝族自治县,巍山彝族回族自治县,永平县,云龙县,洱源县,剑川县,鹤庆县,其它区|德宏傣族景颇族自治州,瑞丽市,芒市,梁河县,盈江县,陇川县,其它区|怒江傈僳族自治州,泸水县,福贡县,贡山独龙族怒族自治县,兰坪白族普米族自治县,其它区|迪庆藏族自治州,香格里拉市,德钦县,维西傈僳族自治县,其它区#西藏自治区$拉萨市,城关区,堆龙德庆区,林周县,当雄县,尼木县,曲水县,达孜县,墨竹工卡县,其它区|日喀则市,桑珠孜区,南木林县,江孜县,定日县,萨迦县,拉孜县,昂仁县,谢通门县,白朗县,仁布县,康马县,定结县,仲巴县,亚东县,吉隆县,聂拉木县,萨嘎县,岗巴县,其它区|昌都市,卡若区,江达县,贡觉县,类乌齐县,丁青县,察雅县,八宿县,左贡县,芒康县,洛隆县,边坝县,其它区|林芝市,巴宜区,工布江达县,米林县,墨脱县,波密县,察隅县,朗县,其它区|山南地区,乃东县,扎囊县,贡嘎县,桑日县,琼结县,曲松县,措美县,洛扎县,加查县,隆子县,错那县,浪卡子县,其它区|那曲地区,那曲县,嘉黎县,比如县,聂荣县,安多县,申扎县,索县,班戈县,巴青县,尼玛县,双湖县,其它区|阿里地区,普兰县,札达县,噶尔县,日土县,革吉县,改则县,措勤县,其它区#陕西省$西安市,新城区,碑林区,莲湖区,灞桥区,未央区,雁塔区,阎良区,临潼区,长安区,高陵区,蓝田县,周至县,户县,其它区|铜川市,王益区,印台区,耀州区,宜君县,其它区|宝鸡市,渭滨区,金台区,陈仓区,凤翔县,岐山县,扶风县,眉县,陇县,千阳县,麟游县,凤县,太白县,其它区|咸阳市,秦都区,杨陵区,渭城区,三原县,泾阳县,乾县,礼泉县,永寿县,彬县,长武县,旬邑县,淳化县,武功县,兴平市,其它区|渭南市,临渭区,华州区,潼关县,大荔县,合阳县,澄城县,蒲城县,白水县,富平县,韩城市,华阴市,其它区|延安市,宝塔区,延长县,延川县,子长县,安塞县,志丹县,吴起县,甘泉县,富县,洛川县,宜川县,黄龙县,黄陵县,其它区|汉中市,汉台区,南郑县,城固县,洋县,西乡县,勉县,宁强县,略阳县,镇巴县,留坝县,佛坪县,其它区|榆林市,榆阳区,横山区,神木县,府谷县,靖边县,定边县,绥德县,米脂县,佳县,吴堡县,清涧县,子洲县,其它区|安康市,汉滨区,汉阴县,石泉县,宁陕县,紫阳县,岚皋县,平利县,镇坪县,旬阳县,白河县,其它区|商洛市,商州区,洛南县,丹凤县,商南县,山阳县,镇安县,柞水县,其它区#甘肃省$兰州市,城关区,七里河区,西固区,安宁区,红古区,永登县,皋兰县,榆中县,其它区|嘉峪关市,新城镇,峪泉镇,文殊镇,雄关区,镜铁区,长城区|金昌市,金川区,永昌县,其它区|白银市,白银区,平川区,靖远县,会宁县,景泰县,其它区|天水市,秦州区,麦积区,清水县,秦安县,甘谷县,武山县,张家川回族自治县,其它区|武威市,凉州区,民勤县,古浪县,天祝藏族自治县,其它区|张掖市,甘州区,肃南裕固族自治县,民乐县,临泽县,高台县,山丹县,其它区|平凉市,崆峒区,泾川县,灵台县,崇信县,华亭县,庄浪县,静宁县,其它区|酒泉市,肃州区,金塔县,瓜州县,肃北蒙古族自治县,阿克塞哈萨克族自治县,玉门市,敦煌市,其它区|庆阳市,西峰区,庆城县,环县,华池县,合水县,正宁县,宁县,镇原县,其它区|定西市,安定区,通渭县,陇西县,渭源县,临洮县,漳县,岷县,其它区|陇南市,武都区,成县,文县,宕昌县,康县,西和县,礼县,徽县,两当县,其它区|临夏回族自治州,临夏市,临夏县,康乐县,永靖县,广河县,和政县,东乡族自治县,积石山保安族东乡族撒拉族自治县,其它区|甘南藏族自治州,合作市,临潭县,卓尼县,舟曲县,迭部县,玛曲县,碌曲县,夏河县,其它区#青海省$西宁市,城东区,城中区,城西区,城北区,大通回族土族自治县,湟中县,湟源县,其它区|海东市,乐都区,平安区,民和回族土族自治县,互助土族自治县,化隆回族自治县,循化撒拉族自治县,其它区|海北藏族自治州,门源回族自治县,祁连县,海晏县,刚察县,其它区|黄南藏族自治州,同仁县,尖扎县,泽库县,河南蒙古族自治县,其它区|海南藏族自治州,共和县,同德县,贵德县,兴海县,贵南县,其它区|果洛藏族自治州,玛沁县,班玛县,甘德县,达日县,久治县,玛多县,其它区|玉树藏族自治州,玉树市,杂多县,称多县,治多县,囊谦县,曲麻莱县,其它区|海西蒙古族藏族自治州,格尔木市,德令哈市,乌兰县,都兰县,天峻县,海西蒙古族藏族自治州直辖,其它区#宁夏回族自治区$银川市,兴庆区,西夏区,金凤区,永宁县,贺兰县,灵武市,其它区|石嘴山市,大武口区,惠农区,平罗县,其它区|吴忠市,利通区,红寺堡区,盐池县,同心县,青铜峡市,其它区|固原市,原州区,西吉县,隆德县,泾源县,彭阳县,其它区|中卫市,沙坡头区,中宁县,海原县,其它区#新疆维吾尔自治区$乌鲁木齐市,天山区,沙依巴克区,新市区,水磨沟区,头屯河区,达坂城区,米东区,乌鲁木齐县,其它区|克拉玛依市,独山子区,克拉玛依区,白碱滩区,乌尔禾区,其它区|吐鲁番市,高昌区,鄯善县,托克逊县,其它区|哈密市,哈密市,巴里坤哈萨克自治县,伊吾县,其它区|昌吉回族自治州,昌吉市,阜康市,呼图壁县,玛纳斯县,奇台县,吉木萨尔县,木垒哈萨克自治县,其它区|博尔塔拉蒙古自治州,博乐市,阿拉山口市,精河县,温泉县,其它区|巴音郭楞蒙古自治州,库尔勒市,轮台县,尉犁县,若羌县,且末县,焉耆回族自治县,和静县,和硕县,博湖县,其它区|阿克苏地区,阿克苏市,温宿县,库车县,沙雅县,新和县,拜城县,乌什县,阿瓦提县,柯坪县,其它区|克孜勒苏柯尔克孜自治州,阿图什市,阿克陶县,阿合奇县,乌恰县,其它区|喀什地区,喀什市,疏附县,疏勒县,英吉沙县,泽普县,莎车县,叶城县,麦盖提县,岳普湖县,伽师县,巴楚县,塔什库尔干塔吉克自治县,其它区|和田地区,和田市,和田县,墨玉县,皮山县,洛浦县,策勒县,于田县,民丰县,其它区|伊犁哈萨克自治州,伊宁市,奎屯市,霍尔果斯市,伊宁县,察布查尔锡伯自治县,霍城县,巩留县,新源县,昭苏县,特克斯县,尼勒克县,其它区|塔城地区,塔城市,乌苏市,额敏县,沙湾县,托里县,裕民县,和布克赛尔蒙古自治县,其它区|阿勒泰地区,阿勒泰市,布尔津县,富蕴县,福海县,哈巴河县,青河县,吉木乃县,其它区|石河子市,新城街道,向阳街道,红山街道,老街街道,东城街道,北泉镇,石河子乡,兵团一五二团,其它区|阿拉尔市,金银川路街道,幸福路街道,青松路街道,南口街道,托喀依乡,工业园区,兵团七团,兵团八团,兵团十团,兵团十一团,兵团十二团,兵团十三团,兵团十四团,兵团十六团,兵团第一师水利水电工程处,兵团第一师塔里木灌区水利管理处,阿拉尔农场,兵团第一师幸福农场,中心监狱,其它区|图木舒克市,齐干却勒街道,前海街道,永安坝街道,兵团四十四团,兵团四十九团,兵团五十团,兵团五十一团,兵团五十三团,兵团图木舒克市喀拉拜勒镇,兵团图木舒克市永安坝,其它区|五家渠市,军垦路街道,青湖路街道,人民路街道,兵团一零一团,兵团一零二团,兵团一零三团,其它区|北屯市,北屯镇,兵团一八三团,兵团一八七团,兵团一八八团,其它区|铁门关市,农二师三十团,兵团二十九团,其它区|双河市,双河市,其它区|可克达拉市,可克达拉市,其它区|昆玉市,兵团二二四团,兵团皮山农场,喀拉喀什镇,乌尔其乡,普恰克其乡,阔依其乡,乌鲁克萨依乡,奴尔乡,博斯坦乡,兵团四十七团,兵团一牧场,兵团一牧场,其它区#台湾省$台北市,松山区,信义区,大安区,中山区,中正区,大同区,万华区,文山区,南港区,内湖区,士林区,北投区,其它区|高雄市,盐埕区,鼓山区,左营区,楠梓区,三民区,新兴区,前金区,苓雅区,前镇区,旗津区,小港区,凤山区,林园区,大寮区,大树区,大社区,仁武区,鸟松区,冈山区,桥头区,燕巢区,田寮区,阿莲区,路竹区,湖内区,茄萣区,永安区,弥陀区,梓官区,旗山区,美浓区,六龟区,甲仙区,杉林区,内门区,茂林区,桃源区,那玛夏区,其它区|基隆市,中正区,七堵区,暖暖区,仁爱区,中山区,安乐区,信义区,其它区|台中市,中区,东区,南区,西区,北区,西屯区,南屯区,北屯区,丰原区,东势区,大甲区,清水区,沙鹿区,梧栖区,后里区,神冈区,潭子区,大雅区,新社区,石冈区,外埔区,大安区,乌日区,大肚区,龙井区,雾峰区,太平区,大里区,和平区,其它区|台南市,东区,南区,北区,安南区,安平区,中西区,新营区,盐水区,白河区,柳营区,后壁区,东山区,麻豆区,下营区,六甲区,官田区,大内区,佳里区,学甲区,西港区,七股区,将军区,北门区,新化区,善化区,新市区,安定区,山上区,玉井区,楠西区,南化区,左镇区,仁德区,归仁区,关庙区,龙崎区,永康区,其它区|新竹市,东区,北区,香山区,其它区|嘉义市,东区,西区,其它区|新北市,板桥区,三重区,中和区,永和区,新庄区,新店区,树林区,莺歌区,三峡区,淡水区,汐止区,瑞芳区,土城区,芦洲区,五股区,泰山区,林口区,深坑区,石碇区,坪林区,三芝区,石门区,八里区,平溪区,双溪区,贡寮区,金山区,万里区,乌来区,其它区|宜兰县,宜兰市,罗东镇,苏澳镇,头城镇,礁溪乡,壮围乡,员山乡,冬山乡,五结乡,三星乡,大同乡,南澳乡,其它区|桃园县,桃园市,中坜市,平镇市,八德市,杨梅市,大溪镇,芦竹乡,大园乡,龟山乡,龙潭乡,新屋乡,观音乡,复兴乡,其它区|新竹县,竹北市,竹东镇,新埔镇,关西镇,湖口乡,新丰乡,芎林乡,橫山乡,北埔乡,宝山乡,峨眉乡,尖石乡,五峰乡,其它区|苗栗县,苗栗市,苑里镇,通霄镇,竹南镇,头份镇,后龙镇,卓兰镇,大湖乡,公馆乡,铜锣乡,南庄乡,头屋乡,三义乡,西湖乡,造桥乡,三湾乡,狮潭乡,泰安乡,其它区|彰化县,彰化市,鹿港镇,和美镇,线西乡,伸港乡,福兴乡,秀水乡,花坛乡,芬园乡,员林镇,溪湖镇,田中镇,大村乡,埔盐乡,埔心乡,永靖乡,社头乡,二水乡,北斗镇,二林镇,田尾乡,埤头乡,芳苑乡,大城乡,竹塘乡,溪州乡,其它区|南投县,南投市,埔里镇,草屯镇,竹山镇,集集镇,名间乡,鹿谷乡,中寮乡,鱼池乡,国姓乡,水里乡,信义乡,仁爱乡,其它区|云林县,斗六市,斗南镇,虎尾镇,西螺镇,土库镇,北港镇,古坑乡,大埤乡,莿桐乡,林内乡,二仑乡,仑背乡,麦寮乡,东势乡,褒忠乡,台西乡,元长乡,四湖乡,口湖乡,水林乡,其它区|嘉义县,太保市,朴子市,布袋镇,大林镇,民雄乡,溪口乡,新港乡,六脚乡,东石乡,义竹乡,鹿草乡,水上乡,中埔乡,竹崎乡,梅山乡,番路乡,大埔乡,阿里山乡,其它区|屏东县,屏东市,潮州镇,东港镇,恒春镇,万丹乡,长治乡,麟洛乡,九如乡,里港乡,盐埔乡,高树乡,万峦乡,内埔乡,竹田乡,新埤乡,枋寮乡,新园乡,崁顶乡,林边乡,南州乡,佳冬乡,琉球乡,车城乡,满州乡,枋山乡,三地门乡,雾台乡,玛家乡,泰武乡,来义乡,春日乡,狮子乡,牡丹乡,其它区|台东县,台东市,成功镇,关山镇,卑南乡,鹿野乡,池上乡,东河乡,长滨乡,太麻里乡,大武乡,绿岛乡,海端乡,延平乡,金峰乡,达仁乡,兰屿乡,其它区|花莲县,花莲市,凤林镇,玉里镇,新城乡,吉安乡,寿丰乡,光复乡,丰滨乡,瑞穗乡,富里乡,秀林乡,万荣乡,卓溪乡,其它区|澎湖县,马公市,湖西乡,白沙乡,西屿乡,望安乡,七美乡,其它区#香港$香港岛,中西区,湾仔区,东区,南区,其它区|九龙,油尖旺区,深水埗区,九龙城区,黄大仙区,观塘区,其它区|新界,荃湾区,屯门区,元朗区,北区,大埔区,西贡区,沙田区,葵青区,离岛区,其它区#澳门$澳门半岛,花地玛堂区,圣安多尼堂区,大堂区,望德堂区,风顺堂区,其它区|氹仔,嘉模堂区,其它区|路环,圣方济各堂区,其它区|路氹城,路氹城,其它区";
if (ShowT)PCAD = SPT + "$" + SCT + "," + SAT + "#" + PCAD; PCAArea = []; PCAP = []; PCAC = []; PCAA = []; PCAN = PCAD.split("#"); for (i = 0; i < PCAN.length; i++){PCAA[i] = []; TArea = PCAN[i].split("$")[1].split("|"); for (j = 0; j < TArea.length; j++){PCAA[i][j] = TArea[j].split(","); if (PCAA[i][j].length == 1)PCAA[i][j][1] = SAT; TArea[j] = TArea[j].split(",")[0]}PCAArea[i] = PCAN[i].split("$")[0] + "," + TArea.join(","); PCAP[i] = PCAArea[i].split(",")[0]; PCAC[i] = PCAArea[i].split(',')}function PCAS(){this.SelP = document.getElementsByName(arguments[0])[0]; this.SelC = document.getElementsByName(arguments[1])[0]; this.SelA = document.getElementsByName(arguments[2])[0]; this.DefP = this.SelA?arguments[3]:arguments[2]; this.DefC = this.SelA?arguments[4]:arguments[3]; this.DefA = this.SelA?arguments[5]:arguments[4]; this.SelP.PCA = this; this.SelC.PCA = this; this.SelP.onchange = function(){PCAS.SetC(this.PCA)}; if (this.SelA)this.SelC.onchange = function(){PCAS.SetA(this.PCA)}; PCAS.SetP(this)}; PCAS.SetP = function(PCA){for (i = 0; i < PCAP.length; i++){PCAPT = PCAPV = PCAP[i]; if (PCAPT == SPT)PCAPV = ""; PCA.SelP.options.add(new Option(PCAPT, PCAPV)); if (PCA.DefP == PCAPV)PCA.SelP[i].selected = true}PCAS.SetC(PCA)}; PCAS.SetC = function(PCA){PI = PCA.SelP.selectedIndex; PCA.SelC.length = 0; for (i = 1; i < PCAC[PI].length; i++){PCACT = PCACV = PCAC[PI][i]; if (PCACT == SCT)PCACV = ""; PCA.SelC.options.add(new Option(PCACT, PCACV)); if (PCA.DefC == PCACV)PCA.SelC[i - 1].selected = true}if (PCA.SelA)PCAS.SetA(PCA)}; PCAS.SetA = function(PCA){PI = PCA.SelP.selectedIndex; CI = PCA.SelC.selectedIndex; PCA.SelA.length = 0; for (i = 1; i < PCAA[PI][CI].length; i++){PCAAT = PCAAV = PCAA[PI][CI][i]; if (PCAAT == SAT)PCAAV = ""; PCA.SelA.options.add(new Option(PCAAT, PCAAV)); if (PCA.DefA == PCAAV)PCA.SelA[i - 1].selected = true} try{$(PCA.SelA).trigger('change')} catch (e){}}