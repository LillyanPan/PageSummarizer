import java.io.BufferedReader;
import java.io.File;
import java.io.FileReader;
import java.io.PrintWriter;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Queue;


public class Framework {
	int n;//number of men (women)
	
	int MenPrefs[][];//preference list of men (n*n)
	int WomenPrefs[][];//preference list of women (n*n)
	
	ArrayList<MatchedPair> MatchedPairsList;//your output should fill this arraylist which is empty at start
	
	public class MatchedPair{
		int man;//man's number
		int woman;//woman's number
		
		public MatchedPair(int Man,int Woman)
		{
			man=Man;
			woman=Woman;
		}
		
		public MatchedPair()
		{	
		}
	}
		
	//reading the input
	void input(String input_name){
		File file = new File(input_name);
		BufferedReader reader = null;
				
		try {
			reader = new BufferedReader(new FileReader(file));
			
			String text = reader.readLine();
			
			String [] parts = text.split(" ");
			n=Integer.parseInt(parts[0]);
			
			MenPrefs=new int[n+1][n];
			WomenPrefs=new int[n+1][n];
			
			for (int i=1;i<=n;i++)
			{
				text=reader.readLine();
				String [] mList=text.split(" ");
				for (int j=0;j<n;j++)
				{
					MenPrefs[i][j]=Integer.parseInt(mList[j]);
				}
			}
			
			for (int i=1;i<=n;i++)
			{
				text=reader.readLine();
				String [] wList=text.split(" ");
				for(int j=0;j<n;j++)
				{
					WomenPrefs[i][j]=Integer.parseInt(wList[j]);
				}
			}
			
			reader.close();
		} catch (Exception e) {
			e.printStackTrace();
		}
	}
	
	//writing the output
	void output(String output_name)
	{
		try{
			PrintWriter writer = new PrintWriter(output_name, "UTF-8");
			
			for(int i=0;i<MatchedPairsList.size();i++)
			{
				writer.println(MatchedPairsList.get(i).man+" "+MatchedPairsList.get(i).woman);
			}
			
			writer.close();
		}catch(Exception e){
			e.printStackTrace();
		}
	}
	
	public Framework(String []Args){
		input(Args[0]);

		MatchedPairsList=new ArrayList<MatchedPair>(); // your should put the final stable matching in this array list
		
		
		/* NOTE
		 * if you want to declare that man x and woman y will get matched in the matching, you can
		 * write a code similar to what follows:
		 * MatchedPair pair=new MatchedPair(x,y);
		 * MatchedPairsList.add(pair);
		*/
		
		//YOUR CODE GOES HERE
		// Create queue of unmatched men
		int[][] ranking = new int[n][n];
		for (int i = 1; i <= n; i++) {
			for (int j = 0; j < n; j++) {
				int rank = WomenPrefs[i][j];
				ranking[i - 1][rank - 1] = j; //index: man, value: rank
			}
		}
// Print ranking
//		for (int i = 0; i < n; i++) {
//		    for (int j = 0; j < n; j++) {
//		        System.out.print(ranking[i][j] + " ");
//		    }
//		    System.out.print("\n");
//		}
		
		ArrayList<Integer> freeMen = new ArrayList<Integer>();
		for (int i = 1; i <= n; i++) {
			freeMen.add(i); //queue 1..n
		}
		//Print freeMen
//		PriorityQueue<Integer>copy = new PriorityQueue<Integer>();
//        copy.addAll(freeMen) ;        
//        Iterator<Integer> through = freeMen.iterator() ;
//        while(through.hasNext() ) {
//                System.out.print(through.next() + " ") ;
//        }
//        System.out.println() ;

		// Create next in line men and women
		int[] next = new int[n];
		Arrays.fill(next, 0); // bc j: 0..n-1; not necessary bc default is 0
		System.out.println(Arrays.toString(next));
		int[] current = new int[n];
		Arrays.fill(current, -1);
		System.out.println(Arrays.toString(current));
		
		while (!freeMen.isEmpty()) {
			int m = freeMen.remove(0); // m: 1..n
			System.out.println("m: " + m);
			////System.out.println("next[m - 1]: " + (next[m - 1]));
			int w = MenPrefs[m][next[m - 1]];
			System.out.println("w: " + w);
			next[m - 1] += 1; // fixed?
			System.out.println("next[m - 1]: " + next[m - 1]);
			// w is free
			System.out.println("current[w - 1]: " + current[w - 1]);
			if (current[w - 1] == -1) {
				current[w - 1] = m; //update
				System.out.println("match");
			}
			else {
				//prefer new to current?
				if (ranking[w - 1][m - 1] < ranking[w - 1][current[w - 1] - 1]) {
					int old = current[w - 1];
					freeMen.add(old);
					current[w - 1] = m;
					System.out.println("switched match");
				}
				//System.out.println("freeMen.size(): " + freeMen.size());
				else { freeMen.add(m); } //uncomment?
			}
			////System.out.println("MatchedPairsList.size(): " + MatchedPairsList.size());
			System.out.println("freeMen: " + freeMen);
			System.out.println("current: " + Arrays.toString(current));
			System.out.println("next: " + Arrays.toString(next) + "\n");
		}
		for (int i = 0; i < n; i++) {
			int woman = i + 1;
			int man = current[i];
			MatchedPair match = new MatchedPair(man, woman);
			MatchedPairsList.add(match);
		}
		
		output(Args[1]);
	}
	
	public static void main(String [] Args)//Strings in Args are the name of the input file followed by the name of the output file
	{
		new Framework(Args);
	}
}
