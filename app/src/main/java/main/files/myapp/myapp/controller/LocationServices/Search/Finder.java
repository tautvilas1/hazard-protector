package main.files.myapp.myapp.controller.LocationServices.Search;

import java.util.ArrayList;

import main.files.myapp.myapp.model.XMLModels.Article;

/**
 * Created by Tautvilas Simkus on 29/12/2016.
 */

/*
This class has the responsibility of finding
articles using particular keywords
 */

public class Finder extends Thread {
    String[] keywords;
    ArrayList<Article> articlesList;

    public Finder(String[] keywords, ArrayList<Article> articlesList) {
        this.keywords = keywords;
        this.articlesList = articlesList;
    }

    public void run() {
        System.out.println(keywords.length+ " and "+articlesList.size());
        ArrayList<Article> articlesListFiltered = new ArrayList<Article>();
        for(Article article: articlesList) {
            for(String keyword : keywords) {
                if(article.toString().toLowerCase().contains(keyword.toLowerCase())) {
                    articlesListFiltered.add(article);
                    System.out.println("Found article: "+article.toString());
                    break;
                }
            }
        }
    }


}
