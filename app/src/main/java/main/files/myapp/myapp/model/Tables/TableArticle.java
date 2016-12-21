package main.files.myapp.myapp.model.Tables;


import org.json.JSONArray;
import org.jsoup.Connection;
import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;
import org.jsoup.nodes.Element;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLConnection;
import java.util.ArrayList;
import java.util.regex.Matcher;
import java.util.regex.Pattern;


public class TableArticle extends Thread {

    public TableArticle() {

    }

    public void run() {
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


            Element body = doc.getElementById("container");

//            try (Writer writer = new BufferedWriter(new OutputStreamWriter(
//                    new FileOutputStream("filename.txt"), "utf-8"))) {
//                writer.write("something");
//            }

            System.out.println("here: "+body.toString());
        }
        catch (IOException e) {
            e.printStackTrace();
        }



    }

}
