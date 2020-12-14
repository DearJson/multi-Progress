<?php


/**
 * 守护进程定义： 即可在后台运行的程序，不会受关闭命令行而停止运作的进程
 * 1.方式1: 可在命令后面加&，例如 php index.php & ,但是terminal终端关闭，进程都会被关闭
 * 2.方式2: 命令行前加nohup命令 ，例如 nohub php index.php，使用正常手段关闭终端虽然不会被关闭进程，但是异常退出终端或终端出现异常退出，都会导致进程被关闭
 * 3.方式3：fork命令，fork当前进程，出来一个子进程，子进程拥有父进程的所有数据。
 */



//fork一个子进程,无论父进程或子进程，接下来都会执行下去，不过父进程返回的子进程的进程ID,子进程则返回0，如果fork失败，则返回-1
$pid = pcntl_fork();

if ($pid==-1){
    exit('fork error');
}elseif ($pid>0){
    //fork成功，并且只有父进程才会进入这里
    exit('parent init');
}elseif ($pid==0){
    //fork成功，并且只有子进程才会进入这里


    //为何需要这个函数？
    //需要使用这个函数将当前子进程提升为该进程的组长，脱离命令行控制
    if (! posix_setsid()){
        exit('setsid error');
    }


    $pid = pcntl_fork();
    if ($pid<0){
        exit( ' fork error. ' );
    }elseif ($pid>0){
        exit(' parent process. ');
    }

    for ($i = 1;$i <= 100;$i++){
        sleep(1);
        file_put_contents('daemon.log',$i,FILE_APPEND);
    }

}
//综合上面的代码，可以借由$pid实现控制父进程退出，子进程后台继续执行耗时间的代码，而不用终端一直运行着，这样可以实现守护进程

