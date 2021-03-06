from scrapy.spider import BaseSpider
from scrapy.http import FormRequest
from postdata.items import PostdataItem

class Post1Spider(BaseSpider):
    name = "post1"
    start_urls = (
        'http://localhost/test.php',
        )

    def parse(self, response):
        for i in range(40000, 50000):
            yield FormRequest("http://127.0.0.1/test.php?a="+ str(i),
            formdata={'data':'abc'},
            callback=self.parse1)

    def parse1(self,response):
        p = PostdataItem()
        p['data'] = response.body
        return None

