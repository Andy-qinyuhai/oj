<?php $show_title="首页 - $OJ_NAME"; ?>
<?php include("template/$OJ_TEMPLATE/header.php");?>
<div class="padding">
    <div class="ui three column grid">
        <div class="eleven wide column">
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
                        $sql_news = "select * FROM `news` WHERE `defunct`!='Y' AND `title`!='faqs.cn' ORDER BY `importance` ASC,`time` DESC LIMIT 10";
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
                        $sql_problems = "select * FROM `problem` ORDER BY `problem_id` DESC LIMIT 5";
                        //$sql_problems = "select * FROM `problem` where defunct='N' ORDER BY `problem_id` DESC LIMIT 5";
                        $result_problems = mysql_query_cache( $sql_problems );
                        if ( $result_problems ) {
                            $i = 1;
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
                            $i = 1;
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
						if(isset($OJ_NOIP_KEYWORD)&&$OJ_NOIP_KEYWORD){
		                     $now =  date('Y-m-d H:i', time());
        	                 $sql="select count(contest_id) from contest where start_time<'$now' and end_time>'$now' and title like '%$OJ_NOIP_KEYWORD%'";
		                     $row=pdo_query($sql);
		                     $cols=$row[0];	
						}
                        $sql_users = "select * FROM `users` where user_id not in (".$OJ_RANK_HIDDEN.") and defunct='N' ORDER BY `solved` DESC LIMIT 15";						
                        if($cols[0]==0) $result_users = mysql_query_cache( $sql_users ); //NOIP赛制比赛时，排名暂时屏蔽
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

