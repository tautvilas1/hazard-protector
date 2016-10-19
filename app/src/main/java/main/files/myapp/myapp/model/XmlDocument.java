package main.files.myapp.myapp.model;

import java.io.DataOutputStream;
import java.io.IOException;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.ProtocolException;
import java.net.URL;

import javax.net.ssl.HttpsURLConnection;

/**
 * Created by Tautvilas on 09/10/2016.
 */
public class XmlDocument extends Thread {
    public String document;


    private final String USER_AGENT = "Mozilla/5.0";

    public XmlDocument(String document) {
        this.document = document;
    }

    public void run() {
        try {
            saveDocument();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public void saveDocument() throws IOException {
        String url = "http://t-simkus.com/final_project/save_article.php";
        URL obj = null;

        try {
            obj = new URL(url);
        }
        catch (MalformedURLException e) {
            e.printStackTrace();
        }

        HttpURLConnection con = null;

        try {
            con = (HttpURLConnection) obj.openConnection();
        }
        catch (IOException e) {
            e.printStackTrace();
        }

        //add reuqest header
        try {
            con.setRequestMethod("POST");
        }
        catch (ProtocolException e) {
            e.printStackTrace();
        }
        con.setRequestProperty("User-Agent", USER_AGENT);
        con.setRequestProperty("Accept-Language", "en-US,en;q=0.5");

        String urlParameters = "title=test&thumbnail=image";

        // Send post request
        con.setDoOutput(true);
        DataOutputStream wr = new DataOutputStream(con.getOutputStream());
        wr.writeBytes(urlParameters);
        wr.flush();
        wr.close();

    }




}
