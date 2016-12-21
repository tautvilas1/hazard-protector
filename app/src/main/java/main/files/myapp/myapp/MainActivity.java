package main.files.myapp.myapp;

import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.View;

//import com.j256.ormlite.support.ConnectionSource;

import java.io.IOException;

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
        TableArticle articles = new TableArticle();
        articles.start();
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



