package so4308554;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.Reader;
import java.net.URL;
import java.nio.charset.Charset;

import org.apache.beam.sdk.transforms.PTransform;
import org.json.JSONException;
import org.json.JSONObject;

public class JSON {

  private static String readAll(Reader rd) throws IOException {
    StringBuilder sb = new StringBuilder();
    int cp;
    while ((cp = rd.read()) != -1) {
      sb.append((char) cp);
    }
    return sb.toString();
  }

  public static JSONObject readJsonFromUrl(String url) throws IOException, JSONException {
    InputStream is = new URL(url).openStream();
    try {
      BufferedReader rd = new BufferedReader(new InputStreamReader(is, Charset.forName("UTF-8")));
      String jsonText = readAll(rd);
      JSONObject json = new JSONObject(jsonText);
      return json;
    } finally {
      is.close();
    }
  }

  public static void main(String[] args) throws IOException, JSONException {
    JSONObject steamjson = readJsonFromUrl("http://api.steampowered.com/ISteamUserStats/GetUserStatsForGame/v0002/?appid=730&key=C1A9D93B831592B9BA3AF5A0D7F24CD9&steamid=76561197984713986&fbclid=IwAR0DJNGLibzm0p92u5TDZKsuQpRxc4mzwAqPWBZQow-r57Yhy_OnO3R0Jfs");
    System.out.println(steamjson.toString());
    System.out.println(steamjson.get("playerstats"));
  }
}