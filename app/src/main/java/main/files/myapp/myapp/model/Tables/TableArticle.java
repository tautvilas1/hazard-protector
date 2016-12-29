package main.files.myapp.myapp.model.Tables;


import android.annotation.TargetApi;
import android.content.Context;
import android.os.Build;
import android.widget.TextView;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import org.jsoup.Connection;
import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;
import org.jsoup.nodes.Element;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.io.PrintWriter;
import java.io.Writer;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLConnection;
import java.util.ArrayList;
import java.util.concurrent.Callable;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import main.files.myapp.myapp.model.XMLModels.Article;


public class TableArticle implements Callable<ArrayList> {

    Context context;

    ArrayList<JSONObject> articlesList = new ArrayList<JSONObject>();

    public TableArticle(Context context) {
        context = context;
    }

    public ArrayList getData() {
        String result = null;
        try {
            Document doc = Jsoup.connect("http://t-simkus.com/final_project/getArticles")
                    .followRedirects(true)
                    .ignoreContentType(true)
                    .timeout(12000) // optional
                    .header("Accept-Language", "pt-BR,pt;q=0.8") // missing
                    .header("Accept-Encoding", "gzip,deflate,sdch") // missing
                    .userAgent("Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.107 Safari/537.36") // missing
                    .referrer("http://www.google.com") // optional
                    .maxBodySize(0)
                    .execute()
                    .parse();


            String body = doc.body().text();
            result = body;

        }
        catch (IOException e) {
            e.printStackTrace();
        }

        articlesToArray(result);

        return jsonToJava(this.articlesList);
    }

    public void articlesToArray(String response) {
        try {
            JSONObject jsonObject = new JSONObject(response);
            JSONArray root = jsonObject.getJSONArray("data");
            for(int i = 0; i < root.length();i++) {
                JSONObject item = (JSONObject) root.get(i);
                articlesList.add(item);
            }
        }
        catch(JSONException e) {
              e.printStackTrace();
        }
    }

    public ArrayList<Article> jsonToJava(ArrayList<JSONObject> articles) {
        ArrayList<Article> articlesList = new ArrayList<Article>();
        for(int i = 0; i < articles.size();i++) {
            Article article = new Article();
            try {
                article.setTitle(articles.get(i).getString("title"));
                article.setCredit(articles.get(i).getString("credit"));
                article.setPublishDate(articles.get(i).getString("publishDate"));
                article.setThumbnail(articles.get(i).getString("thumbnail"));
                article.setDescription(articles.get(i).getString("description"));
                article.setLink(articles.get(i).getString("link"));
                String tags = articles.get(i).getString("tags");
                ArrayList<String> tagsList = new ArrayList<>();
                String[] tagsListTemp = tags.split(",");
                for (String tag : tagsListTemp) {
                    tagsList.add(tag);
                }
                article.setTags(tagsList);
                articlesList.add(article);
            }
            catch (JSONException e) {
                e.printStackTrace();
            }
        }
        return articlesList;
    }

    @Override
    public ArrayList call() throws Exception {
        return getData();
    }
}
