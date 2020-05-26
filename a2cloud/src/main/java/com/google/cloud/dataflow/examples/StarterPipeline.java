/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
package com.google.cloud.dataflow.examples;

import java.io.BufferedReader;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.Reader;
import java.net.URL;
import java.nio.charset.Charset;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashMap;

import org.apache.beam.model.pipeline.v1.RunnerApi.PCollection;
import org.apache.beam.runners.dataflow.options.DataflowPipelineOptions;
import org.apache.beam.sdk.Pipeline;
import org.apache.beam.sdk.io.TextIO;
import org.apache.beam.sdk.io.gcp.bigquery.BigQueryIO;
import org.apache.beam.sdk.io.gcp.bigquery.BigQueryIO.Write.CreateDisposition;
import org.apache.beam.sdk.io.gcp.bigquery.BigQueryIO.Write.WriteDisposition;
import org.apache.beam.sdk.options.PipelineOptions;
import org.apache.beam.sdk.options.PipelineOptionsFactory;
import org.apache.beam.sdk.transforms.Count;
import org.apache.beam.sdk.transforms.DoFn;
import org.apache.beam.sdk.transforms.Filter;
import org.apache.beam.sdk.transforms.FlatMapElements;
import org.apache.beam.sdk.transforms.MapElements;
import org.apache.beam.sdk.transforms.ParDo;
import org.apache.beam.sdk.values.KV;
import org.apache.beam.sdk.values.TypeDescriptors;
import org.json.JSONException;
import org.json.JSONObject;
import org.json.XML;

import com.google.api.services.bigquery.model.TableFieldSchema;
import com.google.api.services.bigquery.model.TableReference;
import com.google.api.services.bigquery.model.TableRow;
import com.google.api.services.bigquery.model.TableSchema;
import com.google.gson.Gson;
import com.google.gson.GsonBuilder;


public class StarterPipeline {
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
	  
		//JSONObject steamjson = readJsonFromUrl("http://api.steampowered.com/ISteamUserStats/GetUserStatsForGame/v0002/?appid=730&key=C1A9D93B831592B9BA3AF5A0D7F24CD9&steamid=76561197984713986&fbclid=IwAR0DJNGLibzm0p92u5TDZKsuQpRxc4mzwAqPWBZQow-r57Yhy_OnO3R0Jfs&format=json");
		//System.out.println(steamjson.toString());
		//System.out.println(steamjson.get("playerstats"));
		//String xml = XML.toString(steamjson);
		//System.out.println(xml);
		//String steamresult = steamjson.toString();

	  public static void main(String[] args) throws IOException, JSONException {
		  TableReference tableRef = new TableReference();
		  tableRef.setProjectId("a2cloud");
		  tableRef.setDatasetId("steam_dataset");
		  tableRef.setTableId("tbl_userStats");

		  List<TableFieldSchema> fieldDefs = new ArrayList<>();
		  fieldDefs.add(new TableFieldSchema().setName("playerstats").setType("RECORD"));
		  fieldDefs.add(new TableFieldSchema().setName("playerstats.achievements").setType("RECORD"));
		  fieldDefs.add(new TableFieldSchema().setName("playerstats.achievements.achieved").setType("INTEGER"));
		  fieldDefs.add(new TableFieldSchema().setName("playerstats.achievements.name").setType("STRING"));
		  fieldDefs.add(new TableFieldSchema().setName("playerstats.gameName").setType("STRING"));
		  fieldDefs.add(new TableFieldSchema().setName("playerstats.stats").setType("RECORD"));
		  fieldDefs.add(new TableFieldSchema().setName("playerstats.stats.value").setType("INTEGER"));
		  fieldDefs.add(new TableFieldSchema().setName("playerstats.stats.name").setType("STRING"));
		  fieldDefs.add(new TableFieldSchema().setName("playerstats.steamID").setType("INTEGER"));
		  
	    PipelineOptions options = PipelineOptionsFactory.create(); 
	    Pipeline pipeLine = Pipeline.create(options);
	    pipeLine
	    .apply("ReadMyFile", 
	            TextIO.read().from("gs://a2cloud_userstats/userStats.json")) 

	    .apply("MapToTableRow", ParDo.of(new DoFn<String, TableRow>() {
	        @ProcessElement
	        public void processElement(ProcessContext c) { 
	            Gson gson = new GsonBuilder().create();
	            HashMap<String, Object> parsedMap = gson.fromJson(c.element().toString(), HashMap.class);

	            TableRow row = new TableRow();
	            row.set("playerstats.stats.value", parsedMap.get("playerstats.stats.value").toString());
	            row.set("playerstats.stats.name", Double.parseDouble(parsedMap.get("playerstats.stats.name").toString()));
	            c.output(row);
	        }
	    }))

	    .apply("CommitToBQTable", BigQueryIO.writeTableRows()
	            .to(tableRef)
	            .withSchema(new TableSchema().setFields(fieldDefs))
	            .withCreateDisposition(CreateDisposition.CREATE_IF_NEEDED)
	            .withWriteDisposition(WriteDisposition.WRITE_APPEND));
	    
	    pipeLine.run().waitUntilFinish();
	  }
	  
	  
	  //on website read from public api and write to file
	  //load file on here to check for updates
	  //post back to server
	  //display on website

}