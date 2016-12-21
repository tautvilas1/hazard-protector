package main.files.myapp.myapp.model.XMLModels;

/**
 * Created by Tautvilas on 16/10/2016.
 */
public class Channel
{
   private String title;

   private String description;

   private String link;

   private String lastBuildDate;

   private String language;

   private String copyright;

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

   public String getLastBuildDate ()
   {
      return lastBuildDate;
   }

   public void setLastBuildDate (String lastBuildDate)
   {
      this.lastBuildDate = lastBuildDate;
   }



   public String getLanguage ()
   {
      return language;
   }

   public void setLanguage (String language)
   {
      this.language = language;
   }

   public String getCopyright ()
   {
      return copyright;
   }

   public void setCopyright (String copyright)
   {
      this.copyright = copyright;
   }

   @Override
   public String toString()
   {
      return "ClassPojo [title = "+title+", description = "+description+", link = "+link+", lastBuildDate = "+lastBuildDate+",language = "+language+", copyright = "+copyright+"]";
   }
}
