<?php

/**
 * 信号： 信号是一种软件中断，也是一种非常典型的异步事件处理方式。
 */


$pid = pcntl_fork();

if ($pid>0){
    cli_set_process_title('php father process');

    pcntl_signal(SIGCHLD,function () use ($pid){
        echo '收到子进程退出信号'.PHP_EOL;
       pcntl_waitpid($pid,$status,WNOHANG);
    });

    while (true){
        sleep(1);
        pcntl_signal_dispatch();
    }
    sleep(2);

}elseif ($pid == 0){

    cli_set_process_title('php children process');

    sleep(10);

}else{
    exit('fork进程失败');
}