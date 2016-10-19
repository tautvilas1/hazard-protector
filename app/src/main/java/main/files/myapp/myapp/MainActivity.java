package main.files.myapp.myapp;

import android.os.Bundle;
import android.provider.DocumentsContract;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.TextView;

//import com.j256.ormlite.support.ConnectionSource;

import org.simpleframework.xml.Serializer;
import org.simpleframework.xml.core.Persister;
import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.Node;

import org.w3c.dom.NodeList;
import org.xml.sax.InputSource;

import java.io.File;
import java.io.IOException;
import java.io.InputStream;
import java.io.StringReader;
import java.sql.Array;
import java.util.concurrent.ExecutionException;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.Future;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;

import main.files.myapp.myapp.controller.LocationServices.NewsFeed.ParseXML;
import main.files.myapp.myapp.model.*;
import main.files.myapp.myapp.model.XMLModels.Item;
import main.files.myapp.myapp.model.XMLModels.NewYorkTimesXmlStructure;

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
        String xmlText;
        String url = "http://rss.nytimes.com/services/xml/rss/nyt/World.xml";
        ExecutorService es = Executors.newSingleThreadExecutor();


        try {
            Future f = es.submit(new ParseXML(url));
            xmlText = (String) f.get();
            TextView content = (TextView) findViewById(R.id.lblContent);

            DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
            DocumentBuilder db = dbf.newDocumentBuilder();

            InputSource is = new InputSource(new StringReader(xmlText));
            Document xml = db.parse(is);

            NodeList nodeList = xml.getElementsByTagName("item");

            for(int i = 0; i <= nodeList.getLength() - 1; i++){
                Element item = (Element) nodeList.item(i);

                String title = item.getAttribute("title");
                String description = item.getAttribute("description");
                String pubDate = item.getAttribute("pubDate");
                String link = item.getAttribute("link");
            }
        }

        catch (InterruptedException e) {
            e.printStackTrace();
        }

        catch (ExecutionException e) {
            e.printStackTrace();
        } catch (Exception e) {
            e.printStackTrace();
        }

    }
}



