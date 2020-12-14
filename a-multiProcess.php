<?php


/**
 * 了解父进程和子进程之前的关系
 */


/**
 * 以下代码证明父进程与子进程之间的数据，堆和栈是隔离的，并不是共享的，我们需要了解到一个概念，就是写时复制，也就是只有当数据发生改变时，
 * 系统内核才会复制一份数据给子进程用，确保两个进程的数据相对隔离，这样做的目的能节约存储空间。
 */
$number = 1;

$pid = pcntl_fork();

if ($pid<0){
    exit('fork进程失败');
}elseif ($pid>0){
    $number += 1;
    echo '父进程执行: number='.$number.PHP_EOL;
}else{
    $number += 1;
    echo '子进程执行: number='.$number.PHP_EOL;
}

echo '结束时: number='.$number.PHP_EOL;





/**
 * 通过以下例子更能明白pcntl_fork的执行过程
 *  1. i=1的情况下，主进程fork了一个进程a,进程a打印了一次，则+1
 *  2. i=2的情况下，主进程再次fork了一个进程b,此时进程a也fork了一个进程c，这时候进程b和进程c都打印了一次，则+2
 *  3. i=3的情况下，主进程，进程a,进程b,进程c,都分别fork()了一次，则+4
 *  所以，最终会打印7次
 */

for ($i=1;$i<=3;$i++){
    $pid = pcntl_fork();
    if ($pid<0){
        exit('fork进程失败');
    }elseif ($pid>0){
        //do nothing...
    }elseif ($pid==0){
        echo 'children progress'.PHP_EOL;
    }
}