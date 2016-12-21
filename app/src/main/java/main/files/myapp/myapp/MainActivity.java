package main.files.myapp.myapp;

import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.text.method.ScrollingMovementMethod;
import android.view.View;
import android.widget.ScrollView;
import android.widget.TextView;

//import com.j256.ormlite.support.ConnectionSource;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import org.w3c.dom.Document;

import java.io.IOException;
import java.util.concurrent.ExecutionException;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.Future;

import main.files.myapp.myapp.controller.LocationServices.NewsFeed.ParseXML;
import main.files.myapp.myapp.controller.LocationServices.NewsTemplates.ParseNYT;
import main.files.myapp.myapp.model.Tables.TableArticle;
import main.files.myapp.myapp.model.Users.User;

public class MainActivity extends AppCompatActivity {

    User user;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

//        createNewUser();
    } //End on create

    public void getArticles(View view) throws IOException {
        TextView content = (TextView) findViewById(R.id.lblContent);
        content.setMovementMethod(new ScrollingMovementMethod());
        ScrollView scrollView = (ScrollView) findViewById(R.id.scrollView);
        ExecutorService es = Executors.newSingleThreadExecutor();
        Future f = es.submit(new TableArticle(this));
        try {
            String response = (String) f.get();

        }
        catch (InterruptedException e) {
            e.printStackTrace();
        }
        catch (ExecutionException e) {
            e.printStackTrace();
        }
    }



    private void createNewUser() {
        user = new User(this);
        user.getAddress();
    }

    public void getFeed(View view) {
        ParseNYT parseNYT = new ParseNYT();
        parseNYT.start();
    }
}



