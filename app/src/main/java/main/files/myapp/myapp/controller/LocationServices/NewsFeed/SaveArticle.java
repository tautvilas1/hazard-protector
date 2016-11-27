package main.files.myapp.myapp.controller.LocationServices.NewsFeed;

import org.jsoup.nodes.Document;

import java.io.IOException;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Date;

import main.files.myapp.myapp.model.XMLModels.Article;

import static org.jsoup.Jsoup.connect;
public class SaveArticle extends Thread{

    Article article;

    public SaveArticle(Article article) {
        super("SaveArticleThread");
        this.article = article;
    }

    @Override
    public void run() {
        try {
            Document doc = connect("http://t-simkus.com/final_project/save_article.php")
                    .data("title", article.getTitle())
                    .data("link", article.getLink())
                    .data("description", article.getDescription())
                    .data("publishDate", article.getPublishDate())
                    .data("credit", article.getCredit())
                    .data("thumbnail", article.getThumbnail())
                    .userAgent("Mozilla")
                    .post();

        }
        catch (IOException e) {
            e.printStackTrace();
        }
    }
}
