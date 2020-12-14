<?php

//设置umask为0，这样，当前进程创建的文件权限则为777
umask(0);

$pid = pcntl_fork();
if ($pid<0){
    exit('fork进程失败');
}elseif ($pid>0){
    exit;
}

//子进程继续
//使用setsid使子进程脱离终端控制，自立门户，
//创建了一个新的会话，而且让这个pid统治这个会话，他既是会话组长，也是进程组长
if (! posix_setsid()){
    exit('setsid失败');
}



$pid = pcntl_fork();
if ($pid<0){
    exit('fork进程失败');
}else if ($pid>0){
    exit;
}

//子进程继续
cli_set_process_title('php master process');

$child_pid = [];

pcntl_signal(SIGCHLD,function(){

    global $child_pid;

    $child_pid_num = count($child_pid);

    if ($child_pid_num>0){

        foreach ($child_pid as $pid_key => $pid_item){

            $wait_result = pcntl_waitpid($pid_item,$status,WNOHANG);

            if ($wait_result == $pid_item || -1 == $wait_result){
                unset($child_pid[$pid_key]);
            }

        }
    }
});


for ($i = 1;$i <= 5;$i++){
    $pid = pcntl_fork();
    if ($pid<0){
        exit;
    }else if ($pid == 0){

        cli_set_process_title('php '.$i.' children process');

        sleep(10);

        exit();


    }else if($pid > 0) {
        $child_pid[] = $pid;
    }
}

//持续dispatch信号
while (true){
    $result = pcntl_signal_dispatch();
    sleep( 1 );
}




