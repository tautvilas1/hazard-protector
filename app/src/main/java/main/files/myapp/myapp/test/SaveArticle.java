package main.files.myapp.myapp.test;

import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;

import java.io.IOException;

import main.files.myapp.myapp.model.XMLModels.Article;

import static org.jsoup.Jsoup.connect;

/**
 * Created by deadlylife on 20/11/2016.
 */

public class SaveArticle extends Thread{

    Article article;

    public SaveArticle(Article article) {
        super("TestThread");
        this.article = article;
    }

    @Override
    public void run() {
        try {
            Document doc = connect("http://t-simkus.com/final_project/save_article.php")
                    .data("title", article.getTitle())
                    .data("link", article.getLink())
                    .data("description", article.getDescription())
                    // and other hidden fields which are being passed in post request.
                    .userAgent("Mozilla")
                    .post();
        }
        catch (IOException e) {
            e.printStackTrace();
        }
    }
}
