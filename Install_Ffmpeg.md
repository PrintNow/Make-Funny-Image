# 安装 `ffmpeg` 依赖命令
> 我不保证以下方法适用于所有 `Linux`，如果无法安装，请 [Google](https://www.google.com)、[Baidu](https://www.baidu.com)、[Bing](https://www.bing.com) 等其他搜索引擎或使用其他途径寻找安装 `ffmpeg` 的办法

## Ubuntu 下安装 `ffmpeg`
> 如果使用一键安装无 `ffmpeg`，可以尝试更换为 [清华大学开源软件镜像站](https://mirror.tuna.tsinghua.edu.cn/help/ubuntu/) 源
```
###########一键安装###########
#添加源。
sudo add-apt-repository ppa:kirillshkrogalev/ffmpeg-next

#更新源。
sudo apt-get update

#下载安装
sudo apt-get install ffmpeg
###########一键安装###########

--------我是分割线--------

##########编译安装（一键安装不可用时可以尝试此方法安装）##########
#需要用到x264库
sudo apt-get install libx264-dev

#安装依赖库
sudo apt-get install libfaac-dev libmp3lame-dev libtheora-dev libvorbis-dev libxvidcore-dev libxext-dev libxfixes-dev

#下载源码
wget https://ffmpeg.org/releases/ffmpeg-3.4.2.tar.bz2
tar -xf ffmpeg-3.4.2.tar.bz2
cd ffmpeg-3.4.2

#配置 ffmpeg
./configure --enable-gpl --enable-version3 --enable-nonfree --enable-postproc  --enable-pthreads --enable-libfaac  --enable-libmp3lame --enable-libtheora --enable-libx264 --enable-libxvid --enable-x11grab --enable-libvorbis --enable-libass

#编译安装
make && make install

#安装完成后执行
ffmpeg -version
#看是否安装成功
##########编译安装（一键安装不可用时可以尝试此方法安装）##########
```

## CentOS 下安装 `ffmpeg`
```
# 安装 epel 库，如果以前装过可以不用
yum install -y epel-release

# 引入 nux.ro 的库
rpm --import http://li.nux.ro/download/nux/RPM-GPG-KEY-nux.ro  
rpm -Uvh http://li.nux.ro/download/nux/dextop/el7/x86_64/nux-dextop-release-0-5.el7.nux.noarch.rpm

# 执行安装
yum install ffmpeg

摘抄自：https://sendya.me/centos-yum-install-ffmpeg-lib/
```

## Windows 下安装 `ffmpeg` 命令
> #### 请参考此篇文章：[在Windows 上安装 FFmpeg 程序](http://blog.51cto.com/helloway/1642247)