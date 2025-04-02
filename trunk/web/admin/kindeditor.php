        <meta charset="utf-8" />
        <link rel="stylesheet" href="../kindeditor/themes/default/default.css" />
        <link rel="stylesheet" href="../kindeditor/plugins/code/prettify.css" />
        <script charset="utf-8" src="../kindeditor/kindeditor.js"></script>
        <script charset="utf-8" src="../kindeditor/lang/zh_CN.js"></script>
        <script charset="utf-8" src="../kindeditor/plugins/code/prettify.js"></script>
        <script>
var kindeditorSeted=false;
if(!kindeditorSeted){

        $(document).ready(window.setTimeout(function (){
                KindEditor.ready(function(K) {
                        let editor1 = K.create('textarea[class="kindeditor"]', {
                                width : '100%',
                                cssPath : '../kindeditor/plugins/code/prettify.css',
                                uploadJson : '../kindeditor/php/upload_json.php',
                                fileManagerJson : '../kindeditor/php/file_manager_json.php',
                                allowFileManager : false,
                                allowImageRemote: true,
                                filterMode:false,
                                cssData: 'body { font-family:"Consolas";font-size: 18px;line-height:150% }  ',
<?php if(isset($OJ_MARKDOWN)&&$OJ_MARKDOWN)
                                echo "designMode:false,";
?>

                                afterCreate : function() {
                                        var self = this;
                                        K.ctrl(document, 13, function() {
                                                self.sync();
                                        });
                                        K.ctrl(self.edit.doc, 13, function() {
                                                self.sync();
                                        });
					        var editerDoc = this.edit.doc;//得到编辑器的文档对象
						//监听粘贴事件, 包括右键粘贴和ctrl+v
						$(editerDoc).bind('paste', null, function (e) {
						    var ele = e.originalEvent.clipboardData.items;
						    for (var i = 0; i < ele.length; ++i) {
							//判断文件类型
							if ( ele[i].kind == 'file' && ele[i].type.indexOf('image/') !== -1 ) {
							    var file = ele[i].getAsFile();//得到二进制数据
							    //创建表单对象，建立name=value的表单数据。
							    var formData = new FormData();
							    formData.append("imgFile",file);//name,value

							    //用jquery Ajax 上传二进制数据
							    $.ajax({
								url : '/kindeditor/php/upload_json.php?dir=image',
								type : 'POST',
								data : formData,
								// 告诉jQuery不要去处理发送的数据
								processData : false,
								// 告诉jQuery不要去设置Content-Type请求头
								contentType : false,
								dataType:"json",
								beforeSend:function(){
								    //console.log("正在进行，请稍候");
								},
								success : function(responseStr) {
								    //上传完之后，生成图片标签回显图片，假定服务器返回url。
								    var src = responseStr.url;
								    var imgTag = "<img src='"+src+"' width='486px' border='0'/>";

								    //console.info(imgTag);
								    //kindeditor提供了一个在焦点位置插入HTML的函数，调用此函数即可。
								    editor1.insertHtml(imgTag);


								},
								error : function(responseStr) {
								    console.log("error");
								}
							    });

							}

						    }
						});
                                }
                                ,
                                        afterBlur: function() {
                                                var self = this;
                                                self.sync();
                                        }

                                ,
                                afterChange: function() {
                                        var self = this;
                                        self.sync();
                                        if( typeof sync === "function")         sync();
                                }
                        });
                        prettyPrint();
                });
        }),100);
         kindeditorSeted=true;
}

        </script>

