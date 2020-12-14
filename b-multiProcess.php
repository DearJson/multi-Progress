<?php

/**
 * 本次了解下僵尸进程和孤儿进程两种进程
 * 孤儿进程：孤儿进程是指子进程还没有执行完，父进程自己就先结束了，导致子进程变得无依无靠，但其实这些进程都会被init(进程ID为1)的进程收养。
 * 僵尸进程：父进程fork出子进程后，没有对其调用wait或者waitpid等对其清理善后工作，那么其会占用进程号以及一些存储该进程其他信息不会被释放
 *          如果有大量的僵尸进程，将因为没有可用的进程号导致系统不能产生新的进程，此即为僵尸进程的危害，应当避免。
 */



//演示并说明孤儿进程的出现，并演示孤儿进程被init进程收养
//$pid = pcntl_fork();
//if( $pid > 0 ){
//    // 显示父进程的进程ID，这个函数可以是getmypid()，也可以用posix_getpid()
//    echo "Father PID:".getmypid().PHP_EOL;
//    // 让父进程停止两秒钟，在这两秒内，子进程的父进程ID还是这个父进程
//    sleep( 2 );
//} else if( 0 == $pid ) {
//    // 让子进程循环10次，每次睡眠1s，然后每秒钟获取一次子进程的父进程进程ID
//    for( $i = 1; $i <= 10; $i++ ){
//        sleep( 1 );
//        // posix_getppid()函数的作用就是获取当前进程的父进程进程ID
//        echo posix_getppid().PHP_EOL;
//    }
//} else {
//    echo "fork error.".PHP_EOL;
//}



//演示僵尸进程的出现，并演示僵尸进程的危害
//首先要明白的，僵尸进程的出现是因为其父进程全程都在，没有退出，但是子进程执行完了，没有调用wait/waitpid则会出现，僵尸进程，如果父进程退出了，
//则不会出现僵尸进程，为什么？因为如果一旦父进程退出了，子进程的会被init进程收养，内核会自动调用wait/waitpid进行回收处理。
//$pid = pcntl_fork();
//if ($pid> 0 ){
//    cli_set_process_title('php father process');
//    sleep(60);
//}elseif ($pid==0){
//    cli_set_process_title('php children process');
//    sleep(10);
//}else{
//    exit('fork 进程失败');
//}




//既然如此，如何避免僵尸进程呢？
//使用pcntl_wait或者pcntl_waitpid都可以进行子进程回收，但是有个问题，使用wait的话父进程就会被阻塞，使用waitpid的话如果父进程先执行完，
//子进程又会变成僵尸进程，就算时间上控制的好，子进程还是会有一段时间会成为僵尸进程，这时候，就可以引入信号概念了


$pid = pcntl_fork();
if ($pid>0){
    cli_set_process_title('php father process');
    sleep(15);
    $wait_result = pcntl_waitpid($pid,$status,WNOHANG);
    print_r($wait_result);
    print_r($status);
}elseif (0 == $pid){
    cli_set_process_title('php child process');
    sleep(10);
}else{
    exit('fork error.'.PHP_EOL);
}