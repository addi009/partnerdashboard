



<style type="text/css">


	
.nav-left{

	background-color: #2a91f5;
	min-height: 100vh;
}

ul li a{

	color: white;
}

.side_bar-icons{

	font-size: 20px;
	padding-top: 10px;
	padding-bottom: 10px;
    margin-left: 10px;

}

.small{
	margin-left: 10px;
	padding-top: 10px;
	padding-bottom: 10px;

}

li:hover{

	background-color: #1566b1;

}

li a:hover{

	color: white;
}

</style>



<nav class="bizVaultNav nav-left">
    <ul>
        <li><a href="<?php echo base_url; ?>tabs/bizVault.php"><i class="fa fa-file-text side_bar-icons"></i></a></li>
        <li><a href="javascript:refresh_folder_content()"><i class="fa fa-retweet side_bar-icons"></i></a></li>
        <li><a href="javascript:notification()"><i class="material-icons side_bar-icons">access_alarm</i></a></li>
        <li><a href="javascript:access_activity()"><i class="fa fa fa-heartbeat side_bar-icons"></i></a></li>
        <li><a href="javascript:activity()"><i class="fa fa-users side_bar-icons"></i></a></li>
    	<li style="margin-top: 400px;"><a href="javascript:;"><i class="fa fa-cog small"></i></a></li>
        <li><a href="javascript:;"><i class="fa fa-info-circle small"></i></a></li>
        <li><a href="javascript:;"><i class="fa fa-question-circle small"></i></a></li>
    </ul>
</nav>






