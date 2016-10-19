package main.files.myapp.myapp.controller.LocationServices.LocationServices;

import android.os.NetworkOnMainThreadException;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.URL;
import java.util.concurrent.Callable;

/**
 * Created by Tautvilas on 14/02/2016.
 */
public class ParseJSON implements Callable<String[]> {
    String lookupLink;

    public ParseJSON(String lookupLink) {
        this.lookupLink = lookupLink;
    }

    @Override
    public String[] call() throws Exception {
        return parse();
    }

    public String[] parse() throws InterruptedException {
        String[] address = new String[2];
        try {
            JSONObject locationJSON = readJsonFromUrl(lookupLink);
            JSONArray resultsJSON = locationJSON.getJSONArray("results");
            JSONObject firstJSON = (JSONObject) resultsJSON.get(0);
            JSONArray addressComponents = firstJSON.getJSONArray("address_components");
            //Find the city
            for(int i = 0 ; i < addressComponents.length();i++) {
                JSONObject component = (JSONObject) addressComponents.get(i);
                String type = component.getString("types");
                if(type.toString().contains("locality") && type.toString().contains("political")) {
                    String city = component.getString("long_name");
                    address[0] = city;
                }
                else if(type.toString().contains("country")) {
                    String country = component.getString("long_name");
                    address[1] = country;
                }
            }

            //address = formatAddress.split(", ");

        } catch (IOException e) {
            e.printStackTrace();
        } catch (JSONException e) {
            e.printStackTrace();
        }
        catch (NetworkOnMainThreadException e) {
            e.printStackTrace();
        }
        return address;
    }


    /*
    @params: url - string to lookup location
    @return: json object with address details
     */

    public static JSONObject readJsonFromUrl(String url) throws IOException, JSONException {
        InputStream is = new URL(url).openStream();
        try {
            BufferedReader rd = new BufferedReader(new InputStreamReader(is));
            String jsonText = null, line;

            while((line = rd.readLine()) != null) {
                jsonText = jsonText + "\n" + line;
            }
            return new JSONObject(jsonText.substring(jsonText.indexOf("{"), jsonText.lastIndexOf("}") + 1));
        } finally {
            is.close();
        }
    }


}
