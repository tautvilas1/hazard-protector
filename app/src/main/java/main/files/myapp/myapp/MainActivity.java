package main.files.myapp.myapp;

import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.TextView;

//import com.j256.ormlite.support.ConnectionSource;

import org.w3c.dom.Document;
import org.w3c.dom.Element;

import org.w3c.dom.NodeList;

import java.util.concurrent.ExecutionException;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.Future;

import main.files.myapp.myapp.controller.LocationServices.NewsFeed.ParseXML;
import main.files.myapp.myapp.model.*;
import main.files.myapp.myapp.model.XMLModels.Article;
import main.files.myapp.myapp.test.SaveArticle;

public class MainActivity extends AppCompatActivity {

    User user;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        createNewUser();
    } //End on create



    private void createNewUser() {
        user = new User(this);
        user.getAddress();
    }

    public void getFeed(View view) {
        Document xmlText;
        String url = "http://rss.nytimes.com/services/xml/rss/nyt/World.xml";
        ExecutorService es = Executors.newSingleThreadExecutor();

        TextView content = null;
        try {
            Future f = es.submit(new ParseXML(url));
            xmlText = (Document) f.get();
            content = (TextView) findViewById(R.id.lblContent);

            NodeList nodeList = xmlText.getElementsByTagName("item");


            for(int i = 0; i <= nodeList.getLength() - 1; i++){
                Element item = (Element) nodeList.item(i);

                Article article = new Article();
                article.setTitle(item.getElementsByTagName("title").item(0).getTextContent());
                article.setDescription(item.getElementsByTagName("description").item(0).getTextContent());
                article.setLink(item.getElementsByTagName("link").item(0).getTextContent());
                article.setPublishDate(item.getElementsByTagName("pubDate").item(0).getTextContent());
//                article.setThumbnail(item.getElementsByTagName("media").item(0).getTextContent());
//                article.setCredit(item.getElementsByTagName("media").item(2).getTextContent());



                //Add all the categories
                for(int b = 0; b < item.getElementsByTagName("category").getLength();b++) {
                    article.getTags().add(item.getElementsByTagName("category").item(b).getTextContent());
                }

                System.out.println(article.toString());


                SaveArticle saveArticle = new SaveArticle(article);
                saveArticle.start();


            }
        }

        catch (InterruptedException e) {
            e.printStackTrace();
        }

        catch (ExecutionException e) {
            e.printStackTrace();
        }

        catch (Exception e) {
            content.setText(e.getMessage());
        }

    }
}



