#include <stdio.h>
#include <curl/curl.h>
#include <stdlib.h>
#include <time.h>
#include <unistd.h>


char *res_buf = NULL;
int shift;

size_t copy_data(void *ptr, size_t size, size_t nmemb, void *stream)
{

	int res_size;

	res_size = size * nmemb;
	res_buf = realloc(res_buf, shift+res_size + 1);
	memcpy(res_buf + shift, ptr, res_size);
	shift += res_size;
	return size * nmemb;

}

int main(int argc, char *argv[])
{
	clock_t start, finish; //生命start和finish是两个时间
	double time; //定义运行时间
	start = clock();
	printf("%d\n", atol(argv[1]));
	pid_t pid;
	int ii;
	int count = atol(argv[2]), procnum = atol(argv[1]);
	
	for(ii=0;ii<procnum;ii++)
	{
		if((pid = fork())>0)

		{

			printf("subproc %d\n",pid);

			CURL *curl; //定义CURL类型的指针
			CURLcode res; //定义CURLcode类型的变量
			int i=0;
		
			curl = curl_easy_init(); //初始化一个CURL类型的指针
			for(i=0;i<count;i++){

				if(curl!=NULL)
				{
					//设置curl选项. 其中CURLOPT_URL是让用户指定url. argv[1]中存放的命令行传进来的网址
					curl_easy_setopt(curl, CURLOPT_URL, "http://127.0.0.1:8080/?test.php");
					curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, &copy_data); 
					//调用curl_easy_perform 执行我们的设置.并进行相关的操作. 在这里只在屏幕上显示出来.
					res = curl_easy_perform(curl);
					//printf("%d", res);
					//清除curl操作.

				}
			}
			curl_easy_cleanup(curl);
			finish = clock(); //获取完成时间
			time = (double)(finish - start) / CLOCKS_PER_SEC; //CLOCKS_PER_SEC，它用来表示一秒钟会有多少个时钟计时单元，进行计算，完成的时间减去开始的时间获得算法运行时间
			printf( "运行时间为\n%f 秒\n",time);
			printf( "\n%f req/秒\n",count * procnum / time);
			exit(0);
		}else
		{
			
		}
	}
	waitpid(-1,NULL);
	//wait();

	return 0;
}
