package main.files.myapp.myapp.controller.LocationServices.NewsFeed;

import android.os.NetworkOnMainThreadException;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.URL;
import java.util.concurrent.Callable;

/**
 * Created by Tautvilas on 14/02/2016.
 */
public class ParseXML implements Callable<String> {
    private String lookupLink;

    public ParseXML(String lookupLink) {
        this.lookupLink = lookupLink;
    }

    @Override
    public String call() throws Exception {
        return Parse();
    }

    public String Parse() throws InterruptedException {
        String xmlText = new String();
        try {
        xmlText = readXmlFromUrl(lookupLink);

        } catch (IOException e) {
            e.printStackTrace();
        }
        catch (NetworkOnMainThreadException e) {
            e.printStackTrace();
        }
        return xmlText;
    }

    /*
    @params: url - string to lookup location
    @return: String object with xml source
     */

    private static String readXmlFromUrl(String url) throws IOException {
        InputStream is = new URL(url).openStream();
        try {
            BufferedReader rd = new BufferedReader(new InputStreamReader(is));
            String xmlText = null, line;

            while((line = rd.readLine()) != null) {
                xmlText = xmlText + "\n" + line;
                System.out.println(line);
            }
            return xmlText;
        } finally {
            is.close();
        }
    }


}
