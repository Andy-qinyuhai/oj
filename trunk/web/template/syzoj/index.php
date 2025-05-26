<?php $show_title="$MSG_HOME - $OJ_NAME"; ?>
<?php include("template/$OJ_TEMPLATE/header.php");?>
<head>

<link rel="stylesheet" href="<?php echo "template/$OJ_TEMPLATE";?>/css/slide.css">

</head>
<div class="padding">
    <div class="ui three column grid">
        <div class="eleven wide column">
            <?php if(file_exists("image/slide1.jpg")){ ?>
            <h4 class="ui top attached block header" style='margin-top: 10px;'><i class="th icon"></i><?php echo $OJ_NAME ?></h4>
            <div class="ui bottom attached center aligned segment carousel">
                <div class="carousel-arrow left" onclick="prevSlide()">&lt;</div> <!-- 左箭头 -->
                <?php for($i=1;file_exists("image/slide$i.jpg");$i++){ ?>
                <div class="carousel-slide <?php if($i==1) echo "active";?>" style="background-image: url('image/slide<?php echo $i ?>.jpg')"></div>
                <?php } ?>
                <div class="carousel-arrow right" onclick="nextSlide()">&gt;</div> <!-- 右箭头 -->
                <div class="carousel-dots">
                    <span class="carousel-dot active" data-index="0"></span>
                    <span class="carousel-dot" data-index="1"></span>
                    <span class="carousel-dot" data-index="2"></span>
                </div>
            </div>
            <?php } ?>

            <h4 class="ui top attached block header"><i class="ui info icon"></i><?php echo $MSG_NEWS;?></h4>
            <div class="ui bottom attached segment">
                <table class="ui very basic table">
                    <thead>
                        <tr>
                            <th width="50%"><?php echo $MSG_TITLE;?></th>
                            <th width="50%"><?php echo $MSG_TIME;?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_news = "select * FROM `news` WHERE `defunct`!='Y' AND `title`!='faqs.cn' ORDER BY `importance` desc,`time` DESC LIMIT 10";
                        $result_news = mysql_query_cache( $sql_news );
                        if ( $result_news ) {
                            foreach ( $result_news as $row ) {
                                echo "<tr>"."<td>"
                                    ."<a href=\"viewnews.php?id=".$row["news_id"]."\">"
                                    .$row["title"]."</a></td>"
                                    ."<td>".$row["time"]."</td>"."</tr>";
                            }
                        }else{
                            echo "check database connection or account ! ";
                        }
                        ?>
                    </tbody>
                </table>
            </div>			     
            <h4 class="ui top attached block header"><i class="ui rss icon"></i> <?php echo $MSG_RECENT_PROBLEM;?> </h4>
            <div class="ui bottom attached segment">
                <table class="ui very basic table">

                    <thead>
                        <tr>
                            <th width="50%"><?php echo $MSG_TITLE;?></th>
                            <th width="50%"><?php echo $MSG_TIME;?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        //$sql_problems = "select * FROM `problem` ORDER BY `problem_id` DESC LIMIT 5";
                        $sql_problems = "select * FROM `problem` where defunct='N' ORDER BY `problem_id` DESC LIMIT 5"; //隐藏未公开的题
                        $result_problems = mysql_query_cache( $sql_problems );
                        if ( !empty($result_problems) ) {                            
                            foreach ( $result_problems as $row ) {
                                echo "<tr>"."<td>"
                                    ."<a href=\"problem.php?id=".$row["problem_id"]."\">"
                                    .$row["title"]."</a></td>"
                                    ."<td>".substr($row["in_date"],0,10)."</td>"."</tr>";
                            }
                        }
                    ?>
                    </tbody>
                </table>
            </div>
			
			<h4 class="ui top attached block header"><i class="trophy icon"></i><?php echo $MSG_RECENT_CONTEST ;?></h4>
            <div class="ui bottom attached center aligned segment">
                <table class="ui very basic table">
                    <thead>
                        <tr>
                            <th width="50%"><?php echo $MSG_CONTEST_NAME;?></th>
                            <th width="50%"><?php echo $MSG_TIME;?></th>							
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $sql_contests = "select * FROM `contest` where defunct='N' ORDER BY `contest_id` DESC LIMIT 5";
                        $result_contests = mysql_query_cache( $sql_contests );
                        if ( $result_contests ) {                            
                            foreach ( $result_contests as $row ) {
								$start_time = strtotime($row['start_time']);
		                        $end_time = strtotime($row['end_time']);
		                        $now = time();
								$length = $end_time-$start_time;
		                        $left = $end_time-$now;
								
								if ($end_time<=$now) {
			                      //past
			                    echo "<tr>"."<td>"
                                    ."<a href=\"contest.php?cid=".$row["contest_id"]."\">"
                                    .$row["title"]."</a></td>"."<td>"
								    ."<span class=text-muted>$MSG_Ended</span>"." "."<span class=text-muted>".$row['end_time']."</span>"
									."</td>"."</tr>";
                                  }
                                else if ($now<$start_time) {
			                     //pending
			                    echo "<tr>"."<td>"
                                    ."<a href=\"contest.php?cid=".$row["contest_id"]."\">"
                                    .$row["title"]."</a></td>"."<td>"
								    ."<span class=text-success>$MSG_Start</span>"." ".$row['start_time']."&nbsp;"
			                        ."<span class=text-success>$MSG_TotalTime"." ".formatTimeLength($length)."</span>"
									."</td>"."</tr>";
		                        }
		                        else {
			                    //running
			                    echo "<tr>"."<td>"
                                    ."<a href=\"contest.php?cid=".$row["contest_id"]."\">"
                                    .$row["title"]."</a></td>"."<td>"
								    ."<span class=text-danger>$MSG_Running</span>"." ".$row['start_time']."&nbsp;"
			                        ."<span class=text-danger>$MSG_LeftTime"." ".formatTimeLength($left)."</span>"
									."</td>"."</tr>";
                                }
                            }
                        }
                    ?>
                    </tbody>
                </table>
            </div>            
			<h4 class="ui top attached block header"><i class="ui calendar icon"></i> <?php echo $MSG_RECENT_SUBMISSION;?> </h4>
            <div class="ui bottom attached segment">
			<table class="ui very basic left aligned table" style="table-layout: fixed; ">
                    <tbody>
                        <?php
                        $sql_news = "select * FROM `news` WHERE `defunct`!='Y' AND `title`='$OJ_INDEX_NEWS_TITLE' ORDER BY `importance` ASC,`time` DESC LIMIT 10";
                        $result_news = mysql_query_cache( $sql_news );
                        if ( $result_news ) {
                            foreach ( $result_news as $row ) {
                                echo "<tr>"."<td>"
                                    .bbcode_to_html($row["content"])."</td></tr>";
                            }
                        }
                        ?>
                         <tr><td>
                                <center> Recent submission :
                                        <?php echo $speed?> .
                                        <div id=submission style="width:80%;height:300px"></div>
                                </center>

                        </td></tr>
                    </tbody>
                </table>
            </div>        
        </div>
        <div class="right floated five wide column">
            <h4 class="ui top attached block header"><i class="ui search icon"></i><?php echo $MSG_SEARCH;?></h4>
            <div class="ui bottom attached segment">
                <form action="problem.php" method="get">
                    <div class="ui search" style="width: 100%; ">
                        <div class="ui left icon input" style="width: 100%; ">
                            <input class="prompt" style="width: 100%; " type="text" placeholder="<?php echo $MSG_PROBLEM_ID ;?> …" name="id">
                            <i class="search icon"></i>
                        </div>
                        <div class="results" style="width: 100%; "></div>
                    </div>
                </form>
            </div>
			<?phpview_month_rank
/* 本月之星  */

$month_id=mysql_query_cache("select solution_id from solution where  in_date<date_add(curdate(),interval -day(curdate())+1 DAY) order by solution_id desc limit 1;");
if(is_array( $month_id) && isset($month_id[0][0]) ) $month_id=$month_id[0][0];else $month_id=0;
//NOIP赛制比赛时，排名暂时屏蔽
if($NOIP_flag[0]==0) $view_month_rank=mysql_query_cache("select user_id,nick,count(distinct(problem_id)) ac from solution where solution_id>$month_id and problem_id>0 and result=4 and first_time=1 group by user_id,nick order by ac desc limit 10");
            if ( is_array($view_month_rank) ) {
        ?>
            <h4 class="ui top attached block header"><i class="ui star icon"></i><?php echo "本月之星"?></h4>
            <div class="ui bottom attached segment">
                <table class="ui very basic table" style="table-layout: fixed; ">
				    <thead>
                        <tr>
                            <th style="width:30px">#</th>
                            <th style="width=70%"><?php echo $MSG_USER_ID;?></th>
                            <th><?php echo $MSG_SOVLED ;?></th>
                        </tr>
                    </thead>
                    <tbody>
        <?php
		                    $i=1;
                            foreach ( $view_month_rank as $row ) {
                                    echo "<tr>".
									        "<td>".$i++."</td>".
                                           "<td><a target='_blank' href='userinfo.php?user=".htmlentities($row[0],ENT_QUOTES,"UTF-8")."'>⭐".htmlentities($row[0],ENT_QUOTES,"UTF-8")."⭐</a></td>".                                    
                                            "<td>".($row[2])."</td>".
                                            "</tr>";

                            }
        ?>
                    </tbody>
                </table>
            </div>
        <?php
            }
/* 本月之星  */
?>	
			
			<h4 class="ui top attached block header"><i class="ui signal icon"></i><?php echo $MSG_RANKLIST;?></h4>
            <div class="ui bottom attached segment">
                <table class="ui very basic table" style="table-layout: fixed; ">
                    <thead>
                        <tr>
                            <th style="width:30px">#</th>
                            <th style="width=70%"><?php echo $MSG_USER_ID;?></th>
                            <th><?php echo $MSG_SOVLED ;?></th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php						
                        $sql_users = "select * FROM `users` where user_id not in (".$OJ_RANK_HIDDEN.") and defunct='N' ORDER BY `solved` DESC LIMIT 15";						
                        if($NOIP_flag[0]==0) $result_users = mysql_query_cache( $sql_users ); //NOIP赛制比赛时，排名暂时屏蔽
                        if ( $result_users ) {
                            $i = 1;
                            foreach ( $result_users as $row ) {
                                echo "<tr>"."<td>".$i++."</td>"."<td>"
                                    ."<a href=\"userinfo.php?user=".$row["user_id"]."\">"
                                    .$row["user_id"]."</a></td>"
                                    ."<td>".$row["solved"]."</td>"."</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
			
        </div>
    </div>
</div>
<?php include("template/$OJ_TEMPLATE/footer.php");?>
<?php if(file_exists("image/slide1.jpg")){ ?>
    <script>
        const slides = document.querySelectorAll('.carousel-slide');
        const dots = document.querySelectorAll('.carousel-dot');
        let currentIndex = 0;
        let autoPlayInterval;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                if (i === index) {
                    slide.classList.add('active');
                } else {
                    slide.classList.remove('active');
                }
            });
            dots.forEach((dot, i) => {
                if (i === index) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
        }

        function nextSlide() {
            currentIndex = (currentIndex + 1) % slides.length;
            showSlide(currentIndex);
        }

        function prevSlide() {
            currentIndex = (currentIndex - 1 + slides.length) % slides.length;
            showSlide(currentIndex);
        }

        // 自动播放，调整为 5 秒切换一次
        autoPlayInterval = setInterval(nextSlide, 5000); 

        dots.forEach(dot => {
            dot.addEventListener('click', () => {
                const targetIndex = parseInt(dot.dataset.index);
                if (targetIndex!== currentIndex) {
                    currentIndex = targetIndex;
                    showSlide(currentIndex);
                    clearInterval(autoPlayInterval);
                    autoPlayInterval = setInterval(nextSlide, 5000);
                }
            });
        });

        // 鼠标悬停暂停自动播放
        const carousel = document.querySelector('.carousel');
        carousel.addEventListener('mouseenter', () => clearInterval(autoPlayInterval));
        carousel.addEventListener('mouseleave', () => autoPlayInterval = setInterval(nextSlide, 5000));
    </script>
 <?php } ?>   
  <script language="javascript" type="text/javascript" src="<?php echo $OJ_CDN_URL?>include/jquery.flot.js"></script>
        <script type="text/javascript">
                $( function () {
                        var d1 = <?php echo json_encode($chart_data_all)?>;
                        var d2 = <?php echo json_encode($chart_data_ac)?>;
                        $.plot( $( "#submission" ), [ {
                                label: "<?php echo $MSG_SUBMIT?>",
                                data: d1,
                                lines: {
                                        show: true
                                }
                        }, {
                                label: "<?php echo $MSG_SOVLED?>",
                                data: d2,
                                bars: {
                                        show: true
                                }
                        } ], {
                                grid: {
                                        backgroundColor: {
                                                colors: [ "#fff", "#eee" ]
                                        }
                                },
                                xaxis: {
                                        mode: "time" //,
                                                //max:(new Date()).getTime(),
                                                //min:(new Date()).getTime()-100*24*3600*1000
                                }
                        } );
                } );
                //alert((new Date()).getTime());
        </script>

