

import java.io.File;
import java.io.IOException;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Random;
import java.awt.List;
import java.io.*;
import java.math.*;
import java.lang.*;

public class ngrams {



	public static void main(String[] args) {
		System.out.println("THIS version is running");
		ArrayList <Double> atheismperplex = new ArrayList <Double>();
		   String stringoutatheism = fileRead("concatatheism.txt");
		   ArrayList <String[]> allwordsatheism = new ArrayList <String[]> ();
		   allwordsatheism= preprocess(stringoutatheism);


		   //unigram stuff

		   HashMap <String, Integer> unimapatheism = unigrammaker(allwordsatheism);
		   HashMap <String, Double> unimapsmoothatheism = unismooth(unimapatheism);

		// atheism  bigram stuff
		   ArrayList <Double> atheismbiperplex = new ArrayList <Double>();
		   ArrayList<ArrayList <String>> atheismbigramarray= bigramarray(allwordsatheism);
		   HashMap<String, HashMap<String, Double>> atheismbigrammap= bigrammapmaker (atheismbigramarray);
		   HashMap<String, HashMap<String, Double>> atheismbigrammapsmooth= smoothBigram (atheismbigrammap);
		   System.out.println("hit1  ");
		   System.out.println("bigram map " + atheismbigrammap);
		   System.out.println("smooth map " + atheismbigrammapsmooth);


		File dir = new File("test_for_classification/file_0.txt");
		   File[] filelist = dir.listFiles();
		   for (File file: filelist) {
			   String path = file.getPath();
			   String stringout2 = fileRead(path);
			   ArrayList <String[]> stringList = new ArrayList<String[]>();
			   stringList= preprocess(stringout2);

			   Double biperplex1= bigramperplexities(stringList, unimapsmoothatheism, atheismbigrammapsmooth);
			   atheismbiperplex.add(biperplex1);

		   }
		System.out.println("perps " + atheismbiperplex);

	}

//			//unigram perplexities for all files
//
//		//atheism:
//		   ArrayList <Double> atheismperplex = new ArrayList <Double>();
//		   String stringoutatheism = fileRead("C:\\Users\\Vidhu\\Documents\\data\\concatatheism.txt");
//		   ArrayList <String[]> allwordsatheism = new ArrayList <String[]> ();
//		   allwordsatheism= preprocess(stringoutatheism);
//
//		   //unigram stuff
//
//		   HashMap <String, Integer> unimapatheism = unigrammaker(allwordsatheism);
//		   HashMap <String, Double> unimapsmoothatheism = unismooth(unimapatheism);
////		   HashMap <String, Double> uniprobsatheism = new HashMap <String, Double> ();
////		   double totalwordsmoothatheism = 0;
////		   for (double f : unimapsmoothatheism.values()) {
////		       totalwordsmoothatheism += f;
////		   }
////		   for (String word: unimapsmoothatheism.keySet()){
////			   uniprobsatheism.put(word, (double)unimapsmoothatheism.get(word)/(totalwordsmoothatheism));
////		  }
//
//
//		   //auto:
//
//		   //unigram stuff
//		   ArrayList <Double> autoperplex = new ArrayList <Double>();
//		   String stringoutauto = fileRead("C:\\Users\\Vidhu\\Documents\\data\\concatauto.txt");
//		   ArrayList <String[]> allwordsauto = preprocess(stringoutauto);
//
//		   HashMap <String, Integer> unimapauto = unigrammaker(allwordsauto);
//		   HashMap <String, Double> unimapsmoothauto = unismooth(unimapauto);
////		   HashMap <String, Double> uniprobsauto = new HashMap <String, Double> ();
////		   double totalwordsmoothauto = 0;
////		   for (double f : unimapsmoothauto.values()) {
////		       totalwordsmoothauto += f;
////		   }
////		   for (String word: unimapsmoothauto.keySet()){
////			   uniprobsauto.put(word, (double)unimapsmoothauto.get(word)/(totalwordsmoothauto));
////		  }
//
//
//		   //graphics
//
//		   //unigram stuff
//		   ArrayList <Double> graphicsperplex = new ArrayList <Double>();
//		   String stringoutgraphics = fileRead("C:\\Users\\Vidhu\\Documents\\data\\concatgraphics.txt");
//		   ArrayList <String[]> allwordsgraphics = preprocess(stringoutgraphics);
//
//		   HashMap <String, Integer> unimapgraphics = unigrammaker(allwordsgraphics);
//		   HashMap <String, Double> unimapsmoothgraphics = unismooth(unimapgraphics);
////		   HashMap <String, Double> uniprobsgraphics = new HashMap <String, Double> ();
////		   double totalwordsmoothgraphics = 0;
////		   for (double f : unimapsmoothgraphics.values()) {
////		       totalwordsmoothgraphics += f;
////		   }
////		   for (String word: unimapsmoothgraphics.keySet()){
////			   uniprobsgraphics.put(word, (double)unimapsmoothgraphics.get(word)/(totalwordsmoothgraphics));
////		  }
//
//
//
//		 //medicine
//
//		   //unigram stuff
//		   ArrayList <Double> medicineperplex = new ArrayList <Double>();
//		   String stringoutmedicine = fileRead("C:\\Users\\Vidhu\\Documents\\data\\concatmedicine.txt");
//		   ArrayList <String[]> allwordsmedicine = preprocess(stringoutmedicine);
//
//		   HashMap <String, Integer> unimapmedicine = unigrammaker(allwordsmedicine);
//		   HashMap <String, Double> unimapsmoothmedicine = unismooth(unimapmedicine);
////		   HashMap <String, Double> uniprobsmedicine = new HashMap <String, Double> ();
////		   double totalwordsmoothmedicine = 0;
////		   for (double f : unimapsmoothmedicine.values()) {
////		       totalwordsmoothmedicine += f;
////		   }
////		   for (String word: unimapsmoothmedicine.keySet()){
////			   uniprobsmedicine.put(word, (double)unimapsmoothmedicine.get(word)/(totalwordsmoothmedicine));
////		  }
//
//
//
//		 //motorcycle
//
//		   //unigram stuff
//		   ArrayList <Double> motorcycleperplex = new ArrayList <Double>();
//		   String stringoutmotorcycle = fileRead("C:\\Users\\Vidhu\\Documents\\data\\concatmotorcycle.txt");
//		   ArrayList <String[]> allwordsmotorcycle = preprocess(stringoutmotorcycle);
//
//		   HashMap <String, Integer> unimapmotorcycle = unigrammaker(allwordsmotorcycle);
//		   HashMap <String, Double> unimapsmoothmotorcycle = unismooth(unimapmotorcycle);
////		   HashMap <String, Double> uniprobsmotorcycle = new HashMap <String, Double> ();
////		   double totalwordsmoothmotorcycle = 0;
////		   for (double f : unimapsmoothmotorcycle.values()) {
////		       totalwordsmoothmotorcycle += f;
////		   }
////		   for (String word: unimapsmoothmotorcycle.keySet()){
////			   uniprobsmotorcycle.put(word, (double)unimapsmoothmotorcycle.get(word)/(totalwordsmoothmotorcycle));
////		  }
//
//		 //religion
//
//		   //unigram stuff
//		   ArrayList <Double> religionperplex = new ArrayList <Double>();
//		   String stringoutreligion = fileRead("C:\\Users\\Vidhu\\Documents\\data\\concatreligion.txt");
//		   ArrayList <String[]> allwordsreligion = preprocess(stringoutreligion);
//
//		   HashMap <String, Integer> unimapreligion = unigrammaker(allwordsreligion);
//		   HashMap <String, Double> unimapsmoothreligion = unismooth(unimapreligion);
////		   HashMap <String, Double> uniprobsreligion = new HashMap <String, Double> ();
////		   double totalwordsmoothreligion = 0;
////		   for (double f : unimapsmoothreligion.values()) {
////		       totalwordsmoothreligion += f;
////		   }
////		   for (String word: unimapsmoothreligion.keySet()){
////			   uniprobsreligion.put(word, (double)unimapsmoothreligion.get(word)/(totalwordsmoothreligion));
////		  }
//
//
//		 //space
//
//		   //unigram stuff
//		   ArrayList <Double> spaceperplex = new ArrayList <Double>();
//		   String stringoutspace = fileRead("C:\\Users\\Vidhu\\Documents\\data\\concatspace.txt");
//		   ArrayList <String[]> allwordsspace = preprocess(stringoutspace);
//
//		   HashMap <String, Integer> unimapspace = unigrammaker(allwordsspace);
//		   HashMap <String, Double> unimapsmoothspace = unismooth(unimapspace);
////		   HashMap <String, Double> uniprobsspace = new HashMap <String, Double> ();
////		   double totalwordsmoothspace = 0;
////		   for (double f : unimapsmoothspace.values()) {
////		       totalwordsmoothspace += f;
////		   }
////		   for (String word: unimapsmoothspace.keySet()){
////			   uniprobsspace.put(word, (double)unimapsmoothspace.get(word)/(totalwordsmoothspace));
////		  }
//
//		 // space bigram stuff
//		   ArrayList <Double> spacebiperplex = new ArrayList <Double>();
//		   ArrayList<ArrayList <String>> spacebigramarray= bigramarray(allwordsspace);
//		   HashMap<String, HashMap<String, Double>> spacebigrammap= bigrammapmaker (spacebigramarray);
//		   HashMap<String, HashMap<String, Double>> spacebigrammapsmooth= smoothBigram (spacebigrammap);
//
//
//		 //religion bigram stuff
//		   ArrayList <Double> religionbiperplex = new ArrayList <Double>();
//		   ArrayList<ArrayList <String>> religionbigramarray= bigramarray(allwordsreligion);
//		   HashMap<String, HashMap<String, Double>> religionbigrammap= bigrammapmaker (religionbigramarray);
//		   HashMap<String, HashMap<String, Double>> religionbigrammapsmooth= smoothBigram (religionbigrammap);
//
//
//			 //motorcycle bigram stuff
//		   ArrayList <Double> motorcyclebiperplex = new ArrayList <Double>();
//		   ArrayList<ArrayList <String>> motorcyclebigramarray= bigramarray(allwordsmotorcycle);
//		   HashMap<String, HashMap<String, Double>> motorcyclebigrammap= bigrammapmaker (motorcyclebigramarray);
//		   HashMap<String, HashMap<String, Double>> motorcyclebigrammapsmooth= smoothBigram (motorcyclebigrammap);
//
//
//			 //medicine bigram stuff
//		   ArrayList <Double> medicinebiperplex = new ArrayList <Double>();
//		   ArrayList<ArrayList <String>> medicinebigramarray= bigramarray(allwordsmedicine);
//		   HashMap<String, HashMap<String, Double>> medicinebigrammap= bigrammapmaker (medicinebigramarray);
//		   HashMap<String, HashMap<String, Double>> medicinebigrammapsmooth= smoothBigram (medicinebigrammap);
//
//
//			 //graphics bigram stuff
//		   ArrayList <Double> graphicsbiperplex = new ArrayList <Double>();
//		   ArrayList<ArrayList <String>> graphicsbigramarray= bigramarray(allwordsgraphics);
//		   HashMap<String, HashMap<String, Double>> graphicsbigrammap= bigrammapmaker (graphicsbigramarray);
//		   HashMap<String, HashMap<String, Double>> graphicsbigrammapsmooth= smoothBigram (graphicsbigrammap);
//
//
//			 //auto bigram stuff
//		   ArrayList <Double> autobiperplex = new ArrayList <Double>();
//		   ArrayList<ArrayList <String>> autobigramarray= bigramarray(allwordsauto);
//		   HashMap<String, HashMap<String, Double>> autobigrammap= bigrammapmaker (autobigramarray);
//		   HashMap<String, HashMap<String, Double>> autobigrammapsmooth= smoothBigram (autobigrammap);
//
//
//			// atheism  bigram stuff
//		   ArrayList <Double> atheismbiperplex = new ArrayList <Double>();
//		   ArrayList<ArrayList <String>> atheismbigramarray= bigramarray(allwordsatheism);
//		   HashMap<String, HashMap<String, Double>> atheismbigrammap= bigrammapmaker (atheismbigramarray);
//		   HashMap<String, HashMap<String, Double>> atheismbigrammapsmooth= smoothBigram (atheismbigrammap);
//
//
//
//		   //test files
//
//		   File dir = new File("C:\\Users\\Vidhu\\Documents\\data\\test_for_classification");
//		   File[] filelist = dir.listFiles();
//		   for (File file: filelist) {
//			   String path = file.getPath();
//			   String stringout2 = fileRead(path);
//			   ArrayList <String[]> stringList = new ArrayList<String[]>();
//			   stringList= preprocess(stringout2);
//
//
////			   Double uniperplex1= unigramperplexity(uniprobsatheism, stringList, totalwordsmoothatheism);
////			   atheismperplex.add(uniperplex1);
////			   Double uniperplex2= unigramperplexity(uniprobsauto, stringList, totalwordsmoothauto);
////			   autoperplex.add(uniperplex2);
////			   Double uniperplex3= unigramperplexity(uniprobsgraphics, stringList, totalwordsmoothgraphics);
////			   graphicsperplex.add(uniperplex3);
////			   Double uniperplex4= unigramperplexity(uniprobsmedicine, stringList, totalwordsmoothmedicine);
////			   medicineperplex.add(uniperplex4);
////			   Double uniperplex5= unigramperplexity(uniprobsmotorcycle, stringList, totalwordsmoothmotorcycle);
////			   motorcycleperplex.add(uniperplex5);
////			   Double uniperplex6= unigramperplexity(uniprobsreligion, stringList, totalwordsmoothreligion);
////			   religionperplex.add(uniperplex6);
////			   Double uniperplex7= unigramperplexity(uniprobsspace, stringList, totalwordsmoothspace);
////			   spaceperplex.add(uniperplex7);
//
//			   Double biperplex1= bigramperplexities(stringList, unimapsmoothatheism, atheismbigrammapsmooth);
//			   atheismbiperplex.add(biperplex1);
//			   Double biperplex2= bigramperplexities(stringList, unimapsmoothauto, autobigrammapsmooth);
//			   autobiperplex.add(biperplex2);
//			   Double biperplex3= bigramperplexities(stringList, unimapsmoothgraphics, graphicsbigrammapsmooth);
//			   graphicsbiperplex.add(biperplex3);
//			   Double biperplex4= bigramperplexities(stringList, unimapsmoothmedicine, medicinebigrammapsmooth);
//			   medicinebiperplex.add(biperplex4);
//			   Double biperplex5= bigramperplexities(stringList, unimapsmoothmotorcycle, motorcyclebigrammapsmooth);
//			   motorcyclebiperplex.add(biperplex5);
//			   Double biperplex6= bigramperplexities(stringList, unimapsmoothreligion, religionbigrammapsmooth);
//			   religionbiperplex.add(biperplex6);
//			   Double biperplex7= bigramperplexities(stringList, unimapsmoothspace, spacebigrammapsmooth);
//			   spacebiperplex.add(biperplex7);
//		   }
//
//		   ArrayList <ArrayList<Double>> allbigramperplexities = new ArrayList <ArrayList<Double>>();
//		   allbigramperplexities.add(atheismbiperplex);
//		   allbigramperplexities.add(autobiperplex);
//		   allbigramperplexities.add(graphicsbiperplex);
//		   allbigramperplexities.add(medicinebiperplex);
//		   allbigramperplexities.add(motorcyclebiperplex);
//		   allbigramperplexities.add(religionbiperplex);
//		   allbigramperplexities.add(spacebiperplex);
//
//		   ArrayList<String> topics = new ArrayList <String>();
//		   topics.add("atheism");
//		   topics.add("auto");
//		   topics.add("computer_graphics");
//		   topics.add("medicine");
//		   topics.add("motorcycles");
//		   topics.add("religion");
//		   topics.add("space");
//
//
//		   int parsebi= 0;
//		   int k = 0;
//
//		   while (parsebi<atheismbiperplex.size()){
//			   ArrayList <Double> onefilevalues = new ArrayList<Double>();
//			   //add perplexity values corresponding to one test file (for each topic set) to arraylist
//			   for (ArrayList <Double> topic: allbigramperplexities){
//				   onefilevalues.add(topic.get(parsebi));
//			    }
//
//
//
//		   //find maximum value of perplexity
//		   int maxposition= findmaximum(onefilevalues);
//		   //System.out.println(onefilevalues);
//		   //System.out.println("for bigrams: this file (" + parsebi + ")  belongs in " + topics.get(maxposition));
//		   System.out.println("file_" + k + ".txt, " + maxposition);
//		   k++;
//		   parsebi++;
//		   		}


//		   System.out.println("atheism: " + atheismperplex);
//		   System.out.println("auto: " + autoperplex);
//		   System.out.println("graphics: " + graphicsperplex);
//		   System.out.println("medicine: " + medicineperplex);



//		   ArrayList <ArrayList<Double>> allperplexities = new ArrayList <ArrayList<Double>>();
//		   allperplexities.add(atheismperplex);
//		   allperplexities.add(autoperplex);
//		   allperplexities.add(graphicsperplex);
//		   allperplexities.add(medicineperplex);
//		   allperplexities.add(motorcycleperplex);
//		   allperplexities.add(religionperplex);
//		   allperplexities.add(spaceperplex);
//
//		   int parse = 0;
//		   while (parse<atheismperplex.size()){
//			   ArrayList <Double> onefilevalues = new ArrayList<Double>();
//			   //add perplexity values corresponding to one test file (for each topic set) to arraylist
//			   for (ArrayList <Double> topic: allperplexities){
//				   onefilevalues.add(topic.get(parse));
//			    }

//		   //find maximum value of perplexity
//		   System.out.println(onefilevalues);
//		   int maxposition= findmaximum(onefilevalues);
//		   System.out.println("for unigrams: this file (" + parse + ")  belongs in " + topics.get(maxposition));
//		   parse++;
//		   		}
//		   }

	public static ArrayList <String[]>unknownremove( HashMap<String, Integer> corpus, ArrayList<String []> test){
		int arraylist = test.size();
		int iter= 0;
		while (iter<arraylist){
			String [] sentence = test.get(iter);
			int sensize= sentence.length;
			int m=0;
			while (m<sensize){
				if (!corpus.keySet().contains((test.get(iter)[m]))){
					test.get(iter)[m]= "UNK";
				}
				m++;
			}
			iter++;
		}
	return test;
	}


	   /* Building Bigrams Map
	    * Runtime O(n^2)
	    * n = length of bigram list
	    */
	public static HashMap<String, HashMap<String, Double>> bigrammapmaker (ArrayList<ArrayList <String>> bigrams){
		   HashMap<String, HashMap<String, Double>> bigramsMap = new HashMap<String, HashMap<String, Double>>();
		   for (ArrayList<String> pair : bigrams) {
			   String firstWord = pair.get(0);
			   HashMap<String, Double> inner = new HashMap<String, Double>();
			   // Build Inner HashMap
			   for (ArrayList<String> pair1 : bigrams) {
				   String firstWord1 = pair1.get(0);
				   String secondWord1 = pair1.get(1);
				   // Bigram begins with same word; note want to consider
				   //	'pair' as well
				   if (firstWord.equals(firstWord1)) {
					   if (inner.containsKey(secondWord1)) {
						   double count = inner.get(secondWord1);
						   inner.put(secondWord1, ++count);
					   }
					   else {
						   inner.put(secondWord1, 1.0);
					   }
				   }
			   }

			   // Only add if map does not contain firstWord; repeats will appear in 'inner' HashMap
			   if (!bigramsMap.containsKey(firstWord)) {
				   bigramsMap.put(firstWord, inner);
			   }
		   }
		return bigramsMap;
	}

	/*smoothed bigram map
	 * bigrammap= map from bigrammap maker function
	 * returns hashmap of hashmaps with smoothed counts
	 * */
	public static HashMap<String, HashMap<String, Double>> smoothBigram (HashMap<String, HashMap<String, Double>> bigrammap){
		HashMap <String[], Double> pairstosmooth = new HashMap <String[], Double>(); //store the pairs to smooth and the number of times the pair occurs
		HashMap<String, HashMap<String, Double>> smoothedbigrams = new HashMap<String, HashMap<String, Double>>();
		smoothedbigrams = bigrammap;
		int numbgrams = 0;
		int numwords = 0;
		for (String c:bigrammap.keySet()){
			HashMap inner = bigrammap.get(c);
			numbgrams = numbgrams + inner.keySet().size();
		}
		numwords = numbgrams + bigrammap.keySet().size();

		int numofzeros= (numwords*numwords) - numbgrams;

		System.out.println("num 0s = " + numofzeros);
		System.out.println("num ws = " + numwords);
		int numofones=0;
		int numoftwos= 0;
		int numofthrees= 0;
		int numoffours= 0;
		int numoffives= 0;
		for (String wone: bigrammap.keySet()){
			for (String wtwo: bigrammap.get(wone).keySet()){
				if(bigrammap.get(wone).get(wtwo)<6){
					Double count= bigrammap.get(wone).get(wtwo);//store number of times pair occurs (c value in good turing formula)
					if (count==0){ numofzeros++;}
					if (count==1){ numofones++;}
					if (count==2){ numoftwos++;}
					if (count==3){ numofthrees++;}
					if (count==4){ numoffours++;}
					if (count==5){ numoffives++;}
					String [] pair = {wone, wtwo};
					pairstosmooth.put(pair, count);
				}
			}
		}

		for (String [] changepair: pairstosmooth.keySet()){
			HashMap<String, Double> temp = new HashMap <String, Double>();
			temp = bigrammap.get(changepair[0]);// inner hashmap
			double countchange = pairstosmooth.get(changepair);
			double newcount= 0;
			if (countchange==0){
				newcount = (countchange +1)*((double)numofones/numofzeros);
			}
			if (countchange==1){
				newcount = (countchange +1)*((double)numoftwos/numofones);
			}
			if (countchange==2){
				newcount = (countchange +1)*((double)numofthrees/numoftwos);
			}
			if (countchange==3){
				newcount = (countchange +1)*((double)numoffours/numofthrees);
			}
			if (countchange==4){
				newcount = (countchange +1)*((double)numoffives/numoffours);
			}
			System.out.println(changepair + " " +  newcount );
			if (newcount != 0) {temp.put(changepair[1], newcount);} //update inner hashmap }
			else{ temp.put(changepair[1], countchange);}; //if smoothed value = 0, use old value
			smoothedbigrams.put(changepair[0], temp); //update outer smoothed hashmap

		}

		return smoothedbigrams;
	}
	/*Perplexity of sentence for bigrams;
	    *creates array list with perplexities of each sentence in corpus
	    */
	public static double bigramperplexities (ArrayList <String[]> testtext, HashMap<String, Double> unigrams, HashMap<String, HashMap<String, Double>> bigramsMap ){
		ArrayList <Double> sentenceperplexities = new ArrayList <Double>();
		int netlength= testtext.size();
		for (String[] c: testtext){
			int p = 0;
			while (p<(c.length-1)){
					double prob = 0;
					ArrayList <String> pair = new ArrayList <String>();
					pair.add((c[p]));
					pair.add((c[p+1]));
					prob = prob+ Math.log(calcBigramProb(pair,unigrams, bigramsMap ));
					p++;
					sentenceperplexities.add(prob);

		}}

		//perplexity for entire text
		double netperplex = 0;
		for (double c: sentenceperplexities){
			netperplex+= c;

		}
		netperplex= Math.exp((netperplex*-1)*((float)1/(netlength)));

		return netperplex;
	}

	   /* Calculate bigram probability
	    * Runtime O(1)
	    */
	public static double calcBigramProb(ArrayList<String> pair, HashMap<String, Double> unigrams, HashMap<String, HashMap<String, Double>> bigramsMap ) {
		int numbgrams = 0;
		int numwords = 0;
		for (String c:bigramsMap.keySet()){
			HashMap inner = bigramsMap.get(c);
			numbgrams = numbgrams + inner.keySet().size();
		}
		numwords = numbgrams + bigramsMap.keySet().size();

			long numofzeros= ((long)numwords*(long)numwords)- numbgrams ;

		   double probability = 0.0;
		   double bigramCount = 0.0;
		   double unigramCount = 0.0;

		   double totalwordsunigram = 0;
		   unigrams.put("UNK", 0.0);
		   for (double f : unigrams.values()) {
			   totalwordsunigram += f;
		   }
		   String firstWord = pair.get(0);
		   String secondWord = pair.get(1);
		   boolean containsPair = bigramsMap.containsKey(firstWord) && bigramsMap.get(firstWord).containsKey(secondWord);
		   System.out.println("num bgram (calcBigramProb)" + numbgrams);
		   System.out.println("num 0s (calcBigramProb) = " + numofzeros);
		   System.out.println("num ws (calcBigramProb) = " + numwords);
		   if (containsPair) {
			   bigramCount += bigramsMap.get(firstWord).get(secondWord);
			   unigramCount += unigrams.get(firstWord);
			   probability = bigramCount/unigramCount;
		   }
		   else{ probability = (double)(numofzeros/numwords);}


		   return probability;
	   }


	public static ArrayList<ArrayList <String>> bigramarray (ArrayList <String[]> allwords){

	   //for each set of words (each sentence) put bigrams in array list bigram, then put all bigrams in larger array list bigrams
	   ArrayList<ArrayList<String>> bigrams = new ArrayList <ArrayList <String>>();
	   for ( String [] onesen: allwords){
		   int wordnum = onesen.length;
		   int r=0;
		   while (r<(wordnum-1)) {
			   ArrayList<String> bigram = new ArrayList<String>();
			   bigram.add(onesen [r]);
			   bigram.add(onesen[r+1]);
			   bigrams.add(bigram);
			   r++;
		   		}
	   		}
	   return bigrams;
	   }


	/* position of min val in arraylist */	//(changed to find min but haven't renamed)
	public static int findmaximum (ArrayList <Double> values){
			int limit = values.size();
			double min = Double.MAX_VALUE;
			int minIndex = -1;
			for (int i = 0; i < limit; i++) {
				if (values.get(i).isInfinite()){minIndex = 0;}
				else{
					double value = values.get(i);
				    if (value <= min) {
				        min = value;
				        minIndex = i;
			    }
				}
			}
			return minIndex;
	}

	/* unigrams smoothing
	 * Good Turing smoothing implemented for all unigrams which occur fewer than four times*/
	public static HashMap <String, Double> unismooth(HashMap<String, Integer> unigrams){
	HashMap <String, Double> unigramcountsmooth = new HashMap <String, Double>();
	   for (String keystring: unigrams.keySet()){
		   int num = unigrams.get(keystring);
		   int numeq= 1;
		   int numplusone = 0;
		   for (String keystringsub: unigrams.keySet()){
			   if (unigrams.get(keystringsub)<4){
			   if (keystringsub != keystring){
				   if (unigrams.get(keystringsub)==num){
					   numeq++;
				   }
				   if (unigrams.get(keystringsub)==(num +1)){
					   numplusone++;
				   }
			   }
			if (numplusone != 0) {//if Nc+1 != 0
				double newcount = (num +1)*((double)numplusone/numeq);
				unigramcountsmooth.put(keystring, newcount);
			}
			else{
				unigramcountsmooth.put(keystring, (double)num);
			}
		  }
		   else{unigramcountsmooth.put((keystring), (double)unigrams.get(keystring));}
			  	 }
	   }
	   return unigramcountsmooth;
	}

	/*file read function */
	public static String fileRead(String fileurl){

			File file = new File(fileurl);
		  	BufferedReader textin;

		  	StringBuilder stringconstruct = new StringBuilder();
		    String line = null;
		    try {
				textin = new BufferedReader(new FileReader (file));
				while ((line = textin.readLine()) != null) {
					stringconstruct.append(line);
				}
			} catch (IOException e) {
				//  Auto-generated catch block
				e.printStackTrace();
			}

		   String stringout = stringconstruct.toString();
		   stringout = stringout.toLowerCase();
		   return stringout;
	}

	 /*Preprocessing function
	 * returns array list of all words in corpus
	 */
	public static ArrayList <String []> preprocess(String stringout){
	//split into sentences
	   String [] sentences = stringout.split("[.!?]");
	   int n = sentences.length;
	   int i=0;
	   ArrayList <String> senten= new ArrayList <String>();

	   while (i < n) {

		   //remove extraneous parts of corpus
		   if (sentences[i].contains( "edu" ) ||
				sentences[i].contains("@" ) ||
				sentences[i].contains("from : " ) ||
				sentences[i].contains("com " ) ||
				sentences[i].contains("/ " ) ||
				sentences[i].contains("re : " )) {
			    sentences[i] = "";
		   }

		   //remove strings with fewer than two words
		   int spaces = 0;

		   for (char c: sentences[i].toCharArray()){
			   if (c== ' ') {spaces ++;}

		   }
		   if (spaces < 2) {
			   sentences[i]="";
		   }


		   //build arraylist without empty elements
		   if (sentences[i] != ""){
			   senten.add(sentences[i]);
		   }

		   i++;
	   }

	   int len= senten.size();
	   int s = 0;
	   while (s<len) {

		   //add beginning and end of sentence markers
		   senten.set(s, "s!" + " " + senten.get(s) + " " + "e!");
		   s++;
	   }


	   //Split each sentence into words, store each set of word arrays in ArrayList allwords
	   ArrayList <String []> allwords = new ArrayList <String []> ();
	   for (String c: senten) {

		   //replace special characters
		   c= c.replaceAll("[^'!a-zA-Z]+"," ");
		   c= c.replaceAll("  ", " ");
		   String [] words = c.split("\\s");
		   allwords.add(words);

	   }
	return allwords;}


	   /*Perplexity of sentence for unigram;
	    * creates array list with perplexities of each sentence in corpus
	    */
	public static double unigramperplexity(HashMap<String, Double> probabilities, ArrayList<String[]> testtext, Double size){
		//preprocess test text
		//ArrayList <String []> processedtext = preprocess(testtext);
		ArrayList <Double> sentenceperplexities = new ArrayList <Double>();
		ArrayList <Double> onesentenceperplexities = new ArrayList <Double>();

		int netlength= 0;
		for (String [] c : testtext){
			int length= c.length;
			netlength= netlength +length;
			if (netlength != 0){
			int p=0;
			while(p<(length)){
				if (probabilities.keySet().contains(c[p])){
					double temp = probabilities.get(c[p]);
					onesentenceperplexities.add(temp);
				}

				//unknown word handling
//				else {
//					if (probabilities.keySet().contains("UNK")== false){probabilities.put("UNK", (double)1.0/size);}
//					else {probabilities.put("UNK", (double)((probabilities.get("UNK")+1)/size));}
//					double temp = probabilities.get("UNK");
//					onesentenceperplexities.add(temp);
//
//				}
			p++;
			}
			Double perplex = 0.0;
			int k=0;
			while ( k < (onesentenceperplexities.size())){
				perplex= perplex+Math.log(onesentenceperplexities.get(k));
				k++;
			}
			sentenceperplexities.add(perplex);
		}}
		Double netperplex = 0.0;
		for (double c1: sentenceperplexities){
			netperplex= netperplex +c1;
		}

		if(netlength==0) {System.out.println("caught");}
		netperplex= Math.exp(netperplex*-1*((float)1/netlength));

		return netperplex;
	 }



	  /* Create unigrams + count */
	public static HashMap<String, Integer> unigrammaker (ArrayList <String []> allwords){
	HashMap<String, Integer> unigrams = new HashMap <String, Integer>();

	   for (String[] c: allwords){

		   int sentenlen = c.length;
		   int p =0;
		   while ( p <sentenlen){
			   if (unigrams.containsKey(c[p])){
				  int num=  unigrams.get(c[p]) +1;
				  unigrams.put(c[p], num);
			   }
			   else {
				   unigrams.put(c[p], 1);
			   }
			   p++;
		   }
	   }
	   return unigrams;
	}





	   /* Calculate unigram probability
	    * Runtime O(1)
	    * Parameters:
	    * - word: word to calculate probability
	    * - unigrams: HashMap of unigrams and counts
	    * - numWords: number of words in entire corpus
	    */
	 public static double calcUnigramProb(String word, HashMap<String, Integer> unigrams, int numWords) {
		   double probability = 0.0;
		   double unigramCount = 0.0;
		   if (unigrams.containsKey(word)) {
			   unigramCount += unigrams.get(word);
			   probability = unigramCount/numWords;
		   }
		   return probability;
	   }

	  	/* Funtion to print nested hashmap */
	 public static void printMapofMap(HashMap<String, HashMap<String, Integer>> map) {
	  		for (Map.Entry<String, HashMap<String, Integer>> entry : map.entrySet()) {
	  			String key = entry.getKey().toString();
	  			HashMap<String, Integer> value = entry.getValue();
	  			//System.out.println("Key: " + key);
	  			//System.out.println("VALUES");
	  			printMap(value);
	  		}
	  	}

	  	/* Funtion to print hashmap */
	  public static void printMap(HashMap<String, Integer> map) {
	  		for (Map.Entry<String, Integer> entry : map.entrySet()) {
	  			String key = entry.getKey().toString();
	  			Integer value = entry.getValue();
	  			//System.out.println("Inner key: " + key + " -> Inner value: " + value);
	  		}
	  	}
   /* Generates a random unigram sentence from the language model
    * Transfers HashMap keys to ArrayList w/ duplicates to easily model random variable
    * later in newWord(lst)
    * Runtime O(n)
    * unigram: HashMap of unigrams to serve as language model
    */
//    public static void generateUnigramSentence(HashMap<String, Integer> unigrams) {
//      // If you're only generating unigrams, comment out the declaration of the bigram map
//      // in main - it takes a very long time to run, and isn't required here.
//      ArrayList<String> lst = new ArrayList<String>();
//      for (Map.Entry<String, Integer> entry : unigrams.entrySet()) {
//        String key = entry.getKey().toString();
//        Integer value = entry.getValue();
//        while (value > 0) {
//          lst.add(key);
//          value--;
//        }
//      }
//      boolean sema = true;
//      while(sema) {
//        String word = newWord(lst);
//        if (word.equals("e!")) {
//          //System.out.println("");
//          sema = false;
//        }
//        else if (!word.equals("s!")) {
//          //System.out.print(word + " ");
//        }
//        else {
//          continue;
//        }
//      }
//    }

//    /* Generates a random bigram sentence from the language model
//     * Runtime O(n)
//     * - init: word to start sentence generation - "s!" recommended
//     * - bigrams: HashMap of bigrams to serve as language model
//     */
//    public static void generateBigramSentence(String init, HashMap<String, HashMap<String, Double>> bigrams) {
//      boolean sema;
//      // Due to large runtime associated with bigram sentence generation, this for loop
//      // by default generates 20 sentences.
//      for(int ii = 0; ii < 20; ii++) {
//        sema = true;
//        //System.out.println("New sentence:");
//        ArrayList<String> lst = new ArrayList<String>();
//        HashMap<String, Double> inner = bigrams.get(init);
//        for(Map.Entry<String, Double> entry : inner.entrySet()) {
//          String key = entry.getKey().toString();
//          Integer value = entry.getValue();
//          while (value > 0) {
//            lst.add(key);
//            value--;
//          }
//        }
//        while(sema) {
//          String word = newWord(lst);
//          if (word.equals("e!")) {
//            //System.out.println("");
//            sema = false;
//          }
//          else if (!word.contentEquals("s!")) {
//            //System.out.print(word + " ");
//          }
//          else {
//            continue;
//          }
//          lst = new ArrayList<String>();
//          try {
//            HashMap<String, Integer> inner2 = bigrams.get(word);
//            for(Map.Entry<String, Integer> entry2 : inner2.entrySet()) {
//              String key2 = entry2.getKey().toString();
//              Integer value2 = entry2.getValue();
//              while (value2 > 0) {
//                lst.add(key2);
//                value2 --;
//              }
//            }
//          }
//          catch (Exception e) {
//            continue;
//          }
//        }
//      }
//    }
//    /* Generates the next word of a sentence when given an ArrayList (with duplicates)
//     * Works both for bigram and unigram cases.
//     * NOTE : words are randomly chosen usinng weighted probability (based on count)
//     * Runtime O(1)
//     * lst: ArrayList of possible next words, weighted by their probabilities
//     */
//    public static String newWord(ArrayList<String> lst) {
//      Random random = new Random();
//      if(lst.size() < 1) {
//        return("e!");
//      }
//      int randomInt = random.nextInt(lst.size());
//      return lst.get(randomInt);
//    }
}