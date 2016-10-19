package main.files.myapp.myapp.controller.LocationServices.LocationServices;

import android.content.Context;
import java.util.concurrent.ExecutionException;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.Future;



/**
 * Created by Tautvilas on 14/02/2016.
 * address[0] - city , address[1] - country
 */
public class CoordinatesToString  {

    Context context;
    public String[] address;
    public double latitude = 0,longitude = 0;
    public GPSTracker gps;

    public CoordinatesToString(Context context) {
        this.context = context;
        convert();
    }


    /*
    @Return: formatted address
     */
    public void convert() {
        gps = new GPSTracker(context);

        if (gps.canGetLocation()) {
            this.latitude = gps.getLatitude();
            this.longitude = gps.getLongitude();

        }

        else {
            gps.showSettingsAlert();
        }

        /*
        Get JSON object
        */
        final String lookupLink = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" + String.valueOf(latitude) + "," + String.valueOf(longitude) + "&key=AIzaSyD_bf5Bw26seqpx7IQRt3pr9zQd6j-tXLs";
        final String temp = "https://maps.googleapis.com/maps/api/geocode/json?latlng=54.835718,23.544920&key=AIzaSyD_bf5Bw26seqpx7IQRt3pr9zQd6j-tXLs";
        System.out.println(temp);

        ExecutorService es = Executors.newSingleThreadExecutor();
        Future f = es.submit(new ParseJSON(temp));

        try {
            address = (String[]) f.get();
        } catch (InterruptedException e) {
            e.printStackTrace();
        } catch (ExecutionException e) {
            e.printStackTrace();
        }

    }

}
