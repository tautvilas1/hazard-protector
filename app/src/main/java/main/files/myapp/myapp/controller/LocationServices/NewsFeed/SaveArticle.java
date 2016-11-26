package main.files.myapp.myapp.controller.LocationServices.NewsFeed;

import org.jsoup.nodes.Document;

import java.io.IOException;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Date;

import main.files.myapp.myapp.model.XMLModels.Article;

import static org.jsoup.Jsoup.connect;

/**
 * Created by deadlylife on 20/11/2016.
 */

public class SaveArticle extends Thread{

    Article article;

    public SaveArticle(Article article) {
        super("SaveArticleThread");
        this.article = article;
    }

    @Override
    public void run() {
        try {
            DateFormat dateFormat = new SimpleDateFormat("yyyy/MM/dd HH:mm:ss");
            Date date = new Date();
//            System.out.println("Tags in save: "+article.getTagsString());
            //                    .data("tags", article.getTagsString())

            Document doc = connect("http://t-simkus.com/final_project/save_article.php")
                    .data("title", article.getTitle())
                    .data("link", article.getLink())
                    .data("description", article.getDescription())
                    .data("publishDate", article.getPublishDate())
                    .data("credit", article.getCredit())
                    .userAgent("Mozilla")
                    .post();
//                    .data("thumbnail", article.getThumbnail())
//            .data("dateAdded", dateFormat.format(date))

        }
        catch (IOException e) {
            e.printStackTrace();
        }
    }
}
