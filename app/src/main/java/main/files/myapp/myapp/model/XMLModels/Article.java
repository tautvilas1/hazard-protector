package main.files.myapp.myapp.model.XMLModels;

import java.util.ArrayList;

/**
 * Created by Tautvilas on 16/10/2016.
 */
public class Article
{
    private String pubDate;

    private String title;

    private String description;

    private String link;

    private ArrayList<String> tags = new ArrayList<String>();

    public Article() {

    }


    public ArrayList<String> getTags() {
        return tags;
    }

    public void setTags(ArrayList<String> tags) {
        this.tags = tags;
    }

    public String getPubDate ()
    {

        return pubDate;
    }

    public void setPubDate (String pubDate)
    {

        this.pubDate = pubDate;
    }

    public String getTitle ()
    {

        return title;
    }

    public void setTitle (String title)
    {

        this.title = title;
    }

    public String getDescription ()
    {

        return description;
    }

    public void setDescription (String description)
    {

        this.description = description;
    }

    public String getLink ()
    {

        return link;
    }

    public void setLink (String link)
    {

        this.link = link;
    }

    public String toString() {
        return "Title: "+ title + "\n"+
                "Link: "+ link + "\n"+
                "Description: " +description + "\n"+
                "Publish date: " + pubDate;
    }


}