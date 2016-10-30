import java.io.BufferedReader;
import java.io.File;
import java.io.FileReader;
import java.io.IOException;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collection;
import java.util.Collections;
import java.util.HashMap;

public class a2Uncer {

	public static void main(String[] args) {
		// KAGGLE 1
		HashMap<String, ArrayList<Integer>> hm = initHM();
		ArrayList<Integer> res = processBaselineWord(hm);
		for (int i : res) {
			System.out.print(i + "-" + i + ", ");
		}
		System.out.println("\n");

		/* KAGGLE 2 */
//		System.out.println(fileReadSent("test.txt").size());
//		for (String s : fileReadSent("test.txt")) {
//			System.out.println(s);
//		}

//		Process files from directory
		ArrayList<String> totalSent = new ArrayList<>();
		File dir = new File("test-private");
		File[] fileList = dir.listFiles();
		for (File file: fileList) {
			String path = file.getPath();
			ArrayList<String> fileSent = fileReadSent(path);
			totalSent.addAll(fileSent);
		}

		/* TEST */
//		ArrayList<String> totalSent = fileReadSent("test.txt");

		String[] uncWords = new String[]{"vague", "vaguely", "vagueness", "vaguenesses", "vaguer", "vaguest", 
		"unforecasted", "unforseen", "unpredicted", "unquantifiable", "unquantified", "unreconciled", "abeyance", 
		"abeyances", "almost", "alteration", "alterations", "ambiguities", "ambiguity", "ambiguous", "anomalies", "anomalous", 
		"anomalously", "anomaly", "anticipate", "anticipated", "anticipates", "anticipating", "anticipation", "anticipations", 
		"apparent", "apparently", "appear", "appeared", "appearing", "appears", "approximate", "approximated", "approximately", 
		"approximates", "approximating", "approximation", "approximations", "arbitrarily", "arbitrariness", "arbitrary", "assume", 
		"assumed", "assumes", "assuming", "assumption", "assumptions", "believe", "believed", "believes", "believing", "cautious", 
		"cautiously", "cautiousness", "clarification", "clarifications", "conceivable", "conceivably", "conditional", "conditionally", 
		"confuses", "confusing", "confusingly", "confusion", "contingencies", "contingency", "contingent", "contingently", 
		"contingents", "could", "crossroad", "crossroads", "depend", "depended", "dependence", "dependencies", "dependency", 
		"dependent", "depending", "depends", "destabilizing", "deviate", "deviated", "deviates", "deviating", "deviation", 
		"deviations", "differ", "differed", "differing", "differs", "doubt", "doubted", "doubtful", "doubts", "exposure", "exposures", 
		"fluctuate", "fluctuated", "fluctuates", "fluctuating", "fluctuation", "fluctuations", "hidden", "hinges", "imprecise", 
		"imprecision", "imprecisions", "improbability", "improbable", "incompleteness", "indefinite", "indefinitely", "indefiniteness", 
		"indeterminable", "indeterminate", "inexact", "inexactness", "instabilities", "instability", "intangible", "intangibles", 
		"likelihood", "may", "maybe", "might", "nearly", "nonassessable", "occasionally", "ordinarily", "pending", "perhaps", 
		"possibilities", "possibility", "possible", "possibly", "precaution", "precautionary", "precautions", "predict", 
		"predictability", "predicted", "predicting", "prediction", "predictions", "predictive", "predictor", "predictors", "predicts", 
		"preliminarily", "preliminary", "presumably", "presume", "presumed", "presumes", "presuming", "presumption", "presumptions", 
		"probabilistic", "probabilities", "probability", "probable", "probably", "random", "randomize", "randomized", "randomizes", 
		"randomizing", "randomly", "randomness", "reassess", "reassessed", "reassesses", "reassessing", "reassessment", "reassessments", 
		"recalculate", "recalculated", "recalculates", "recalculating", "recalculation", "recalculations", "reconsider", "reconsidered", 
		"reconsidering", "reconsiders", "reexamination", "reexamine", "reexamining", "reinterpret", "reinterpretation", 
		"reinterpretations", "reinterpreted", "reinterpreting", "reinterprets", "revise", "revised", "risk", "risked", "riskier", 
		"riskiest", "riskiness", "risking", "risks", "risky", "roughly", "rumors", "seems", "seldom", "seldomly", "sometime", 
		"sometimes", "somewhat", "somewhere", "speculate", "speculated", "speculates", "speculating", "speculation", "speculations", 
		"speculative", "speculatively", "sporadic", "sporadically", "sudden", "suddenly", "suggest", "suggested", "suggesting", 
		"suggests", "susceptibility", "tending", "tentative", "tentatively", "turbulence", "uncertain", "uncertainly", "uncertainties", 
		"uncertainty", "unclear", "unconfirmed", "undecided", "undefined", "undesignated", "undetectable", "undeterminable", 
		"undetermined", "undocumented", "unexpected", "unexpectedly", "unfamiliar", "unfamiliarity", "unguaranteed", "unhedged", 
		"unidentifiable", "unidentified", "unknown", "unknowns", "unobservable", "unplanned", "unpredictability", "unpredictable", 
		"unpredictably", "unproved", "unproven", "unseasonable", "unseasonably", "unsettled", "unspecific", "unspecified", "untested", 
		"unusual", "unusually", "unwritten", "vagaries", "variability", "variable", "variables", "variably", "variance", "variances", 
		"variant", "variants", "variation", "variations", "varied", "varies", "vary", "varying", "volatile", "volatilities", "volatility"};
		ArrayList<Integer> res2 = processBaselineSent(uncWords, totalSent);
		for (int i : res2) {
			System.out.print(i + ", ");
		}
		System.out.println("\n");

	}

	public static HashMap<String, ArrayList<Integer>> initHM() {
		HashMap<String, ArrayList<Integer>> resHM = new HashMap<>();
		String[] lst = new String[]{"vague", "vaguely", "vagueness", "vaguenesses", "vaguer", "vaguest", 
		"unforecasted", "unforseen", "unpredicted", "unquantifiable", "unquantified", "unreconciled", "abeyance", 
		"abeyances", "almost", "alteration", "alterations", "ambiguities", "ambiguity", "ambiguous", "anomalies", "anomalous", 
		"anomalously", "anomaly", "anticipate", "anticipated", "anticipates", "anticipating", "anticipation", "anticipations", 
		"apparent", "apparently", "appear", "appeared", "appearing", "appears", "approximate", "approximated", "approximately", 
		"approximates", "approximating", "approximation", "approximations", "arbitrarily", "arbitrariness", "arbitrary", "assume", 
		"assumed", "assumes", "assuming", "assumption", "assumptions", "believe", "believed", "believes", "believing", "cautious", 
		"cautiously", "cautiousness", "clarification", "clarifications", "conceivable", "conceivably", "conditional", "conditionally", 
		"confuses", "confusing", "confusingly", "confusion", "contingencies", "contingency", "contingent", "contingently", 
		"contingents", "could", "crossroad", "crossroads", "depend", "depended", "dependence", "dependencies", "dependency", 
		"dependent", "depending", "depends", "destabilizing", "deviate", "deviated", "deviates", "deviating", "deviation", 
		"deviations", "differ", "differed", "differing", "differs", "doubt", "doubted", "doubtful", "doubts", "exposure", "exposures", 
		"fluctuate", "fluctuated", "fluctuates", "fluctuating", "fluctuation", "fluctuations", "hidden", "hinges", "imprecise", 
		"imprecision", "imprecisions", "improbability", "improbable", "incompleteness", "indefinite", "indefinitely", "indefiniteness", 
		"indeterminable", "indeterminate", "inexact", "inexactness", "instabilities", "instability", "intangible", "intangibles", 
		"likelihood", "may", "maybe", "might", "nearly", "nonassessable", "occasionally", "ordinarily", "pending", "perhaps", 
		"possibilities", "possibility", "possible", "possibly", "precaution", "precautionary", "precautions", "predict", 
		"predictability", "predicted", "predicting", "prediction", "predictions", "predictive", "predictor", "predictors", "predicts", 
		"preliminarily", "preliminary", "presumably", "presume", "presumed", "presumes", "presuming", "presumption", "presumptions", 
		"probabilistic", "probabilities", "probability", "probable", "probably", "random", "randomize", "randomized", "randomizes", 
		"randomizing", "randomly", "randomness", "reassess", "reassessed", "reassesses", "reassessing", "reassessment", "reassessments", 
		"recalculate", "recalculated", "recalculates", "recalculating", "recalculation", "recalculations", "reconsider", "reconsidered", 
		"reconsidering", "reconsiders", "reexamination", "reexamine", "reexamining", "reinterpret", "reinterpretation", 
		"reinterpretations", "reinterpreted", "reinterpreting", "reinterprets", "revise", "revised", "risk", "risked", "riskier", 
		"riskiest", "riskiness", "risking", "risks", "risky", "roughly", "rumors", "seems", "seldom", "seldomly", "sometime", 
		"sometimes", "somewhat", "somewhere", "speculate", "speculated", "speculates", "speculating", "speculation", "speculations", 
		"speculative", "speculatively", "sporadic", "sporadically", "sudden", "suddenly", "suggest", "suggested", "suggesting", 
		"suggests", "susceptibility", "tending", "tentative", "tentatively", "turbulence", "uncertain", "uncertainly", "uncertainties", 
		"uncertainty", "unclear", "unconfirmed", "undecided", "undefined", "undesignated", "undetectable", "undeterminable", 
		"undetermined", "undocumented", "unexpected", "unexpectedly", "unfamiliar", "unfamiliarity", "unguaranteed", "unhedged", 
		"unidentifiable", "unidentified", "unknown", "unknowns", "unobservable", "unplanned", "unpredictability", "unpredictable", 
		"unpredictably", "unproved", "unproven", "unseasonable", "unseasonably", "unsettled", "unspecific", "unspecified", "untested", 
		"unusual", "unusually", "unwritten", "vagaries", "variability", "variable", "variables", "variably", "variance", "variances", 
		"variant", "variants", "variation", "variations", "varied", "varies", "vary", "varying", "volatile", "volatilities", "volatility"};

		for (String s : lst) {
			ArrayList<Integer> val = new ArrayList<>();
			resHM.put(s, val);
		}

		return resHM;
	}

	public static String fileRead(String fileurl){

		File file = new File(fileurl);
	  	BufferedReader input;

	  	StringBuilder sb = new StringBuilder();
	    String line = null;
	    try {
	    	input = new BufferedReader(new FileReader (file));
			while ((line = input.readLine()) != null) {
				String[] arr = line.split("\\t");
				if (arr[0].trim().equals("") || arr[0].trim().equals(".") || arr[0].trim().equals(",")) {
					continue;
				}
				sb.append(arr[0] + " ");
			}
		} catch (IOException e) {
			//  Auto-generated catch block
			e.printStackTrace();
		}

	   String res = sb.toString();
	   res = res.toLowerCase();
	   return res;
	}

	public static ArrayList<String> fileReadSent(String fileurl){

		File file = new File(fileurl);
	  	BufferedReader input;
	  	ArrayList<String> res = new ArrayList<>();
	  	StringBuilder sb = new StringBuilder();
	    String line = null;
	    try {
	    	input = new BufferedReader(new FileReader (file));
			while ((line = input.readLine()) != null) {
				if (line.trim().equals("")) {
//					String sentence = sb.toString().toLowerCase();
					String sentence = sb.toString();
					res.add(sentence);
					sb = new StringBuilder();
				}
				else {
					String[] arr = line.split("\\t");
					sb.append(arr[0] + " ");
				}
			}
		} catch (IOException e) {
			//  Auto-generated catch block
			e.printStackTrace();
		}

	   return res;
	}

	public static ArrayList<Integer> processBaselineWord(HashMap<String, ArrayList<Integer>> hm) {
		String input = fileRead("private.txt").trim().toLowerCase();
//		System.out.println("INPUT: " + input);
		ArrayList<Integer> res = new ArrayList<>();
		// [\\s]+ not parsing correctly
		String[] inArr = input.split(" +");
		for (int i = 0; i < inArr.length; i++) {
			if (hm.containsKey(inArr[i])) {
				ArrayList<Integer> val = hm.get(inArr[i]);
				val.add(i);
			}
		}
		for (ArrayList<Integer> val : hm.values()) {
			res.addAll(val);
		}
		Collections.sort(res);
		return res;
	}

	public static ArrayList<Integer> processBaselineSent(String[] uncertWords, ArrayList<String> sentences) {
		ArrayList<Integer> res = new ArrayList<>();

		for (int i = 0; i < sentences.size(); i++) {
			String s = sentences.get(i);
			System.out.println("SENTENCE: " + s);
			for (String unStr : uncertWords) {
				if (s.contains(unStr)) {
					System.out.println("matched word: " + unStr);
					res.add(i);
					break;
				}
			}
		}
		Collections.sort(res);
		return res;
	}

}
