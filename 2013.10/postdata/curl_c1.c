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
	time_t start_time, end_time;
	double elapsed_time;

	time( &start_time );
	printf("%d\n", atol(argv[1]));
	pid_t pid;
	int ii;
	int count = atol(argv[2]), procnum = atol(argv[1]);
	
	for(ii=0;ii<procnum;ii++)
	{
		if((pid = fork())>0)
		{
			CURL *curl; 
			CURLcode res; 
			int i=0;
			curl = curl_easy_init(); 
			for(i=0;i<count;i++){
				if(curl!=NULL)
				{

					curl_easy_setopt(curl, CURLOPT_URL, "http://127.0.0.1:8080/?test.php");
					curl_easy_setopt(curl, CURLOPT_POST, 1); 
					curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, &copy_data); 
					curl_easy_setopt(curl, CURLOPT_POSTFIELDS, "data=hello");
					//curl_easy_setopt(curl, CURLOPT_POSTFIELDS, "data=hello");
					res = curl_easy_perform(curl);

				}
			}
			curl_easy_cleanup(curl);
			time( &end_time );
			elapsed_time = difftime(end_time, start_time);
			//printf( "%d, %f\n",i,elapsed_time);
			printf( "\n%f req/秒\n",count * procnum / elapsed_time);
			exit(0);
		}else
		{
			
		}
	}
	waitpid(-1,NULL);
	return 0;
}
