package main.files.myapp.myapp.model;

import android.content.Context;

import main.files.myapp.myapp.controller.LocationServices.LocationServices.CoordinatesToString;

/**
 * Created by Tautvilas on 17/01/2016.
 * This class holds the profile of the user
 * Including the last known location
 */
public class User {

    public String city = null,country = null;
    Context context;

    public User(Context context) {
        this.context = context;
    }

    public void setCity(String city) {
        this.city = city;
    }

    public void setCountry(String country) {
        this.country = country;
    }


    /*
    @Return: formatted address
    */
    public void getAddress() {
        CoordinatesToString cts = new CoordinatesToString(this.context);
        if(cts.gps.canGetLocation()) {
            System.out.println("Location enabled");
        }

        else {
            System.out.println("Location disabled");

        }
        //If address has been parsed
        if(cts.address != null) {
            if(cts.address[0] != null) {
                this.setCity(cts.address[0]);
            }
            if(cts.address[1] != null) {
                this.setCountry(cts.address[1]);
            }
        }
        else {
            System.out.println("Please turn on location services");
        }
    }
}
