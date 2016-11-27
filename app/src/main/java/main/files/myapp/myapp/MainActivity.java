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
import main.files.myapp.myapp.controller.LocationServices.NewsTemplates.ParseNYT;
import main.files.myapp.myapp.model.*;
import main.files.myapp.myapp.model.XMLModels.Article;
import main.files.myapp.myapp.test.SaveArticle;

public class MainActivity extends AppCompatActivity {

    User user;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
//        createNewUser();
    } //End on create



    private void createNewUser() {
        user = new User(this);
        user.getAddress();
    }

    public void getFeed(View view) {
        ParseNYT parseNYT = new ParseNYT();
        parseNYT.start();
    }
}



