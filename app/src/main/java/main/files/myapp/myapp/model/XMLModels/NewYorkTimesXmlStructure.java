package main.files.myapp.myapp.model.XMLModels;

/**
 * Created by deadlylife on 18/10/2016.
 */

public class NewYorkTimesXmlStructure {

    private Rss rss;

    public Rss getRss ()
    {
        return rss;
    }

    public void setRss (Rss rss)
    {
        this.rss = rss;
    }

    @Override
    public String toString()
    {
        return "ClassPojo [rss = "+rss+"]";
    }
}
