import java.io.BufferedReader;
import java.io.File;
import java.io.FileReader;
import java.io.PrintWriter;

public class Framework {
    int n;
    int skillList[][];
    int wrongM[];//the wrong matching
    int s[];
    boolean existsPerfectMatching=false; //means there exists a perfect matching or not.
    int correctM[]; //correctM[i] means the job that p_i does
    //reading the input
    void input(String input_name){
        File file = new File(input_name);
        BufferedReader reader = null;

        try {
            reader = new BufferedReader(new FileReader(file));

            String text = reader.readLine();
            n=Integer.parseInt(text);
            skillList =new int[n][];
            s=new int[n];
            String [] parts;
            for (int i=0;i<n;i++){
                text=reader.readLine();
                parts=text.split(" ");
                s[i]=Integer.parseInt(parts[0]);
                skillList[i]=new int[s[i]];
                for (int j=0;j<s[i];j++) {
                    skillList[i][j]=Integer.parseInt(parts[j+1]);
//                    System.out.println(skillList[i][j]);
                }
            }
            text=reader.readLine();
            parts=text.split(" ");
            wrongM=new int[n];
            for (int i=0;i<n;i++)
                wrongM[i]=Integer.parseInt(parts[i]);
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

            if (existsPerfectMatching)
            {
                writer.println("Yes");
                for (int i=0;i<n;i++)
                    writer.print(correctM[i]+" ");
                writer.println();
            } else
                writer.println("No");


            writer.close();
        }catch(Exception e){
            e.printStackTrace();
        }
    }

    public Framework(String []Args){
        input(Args[0]);


        //You should change the variable existsPerfectMatching to either true (has a perfect matching) or false (no perfect matching)
        //If there is one perfect matching, you should also change the array correctM, to denote the matching you found.
        //If there is no perfect matching, you don't need to change other stuff. 

        //YOUR CODE GOES HERE
        
        /********************************** 
         * Find Bad Matching
        ***********************************/
        existsPerfectMatching = true;
        int source = -1;
        int sink = -1;
        for (int p = 0; p < n; p++) {
        	int wMatchJob = wrongM[p];
//        	Base Case: one person cannot do any jobs
        	if (skillList[p].length == 0) {
        		existsPerfectMatching = false;
        		break;
        	}
        	else {
        		boolean match = false;
        		for (int j = 0; j < skillList[p].length; j++) {
        			match = match || (wMatchJob == skillList[p][j]);
            	}
//        		If match = true, then the person is not wrong; set source and sink
        		if (!match) {
        			source = p;
        			sink = wrongM[p];
        		}
        	}
        }
        if (!existsPerfectMatching) {
        	output(Args[1]);
        	return;
        }
        
        /********************************** 
         * Build Residual Graphs 
        ***********************************/
        
        int resPeopleGraph[][] = new int[n][]; 	// 1 : forward edge to jobs; 0 : backwards edge
        int maxJob[] = new int[n];
        int jobstoPeople[] = new int[n];
        
        ///////// Find lengths of jagged array for residual graphs /////////
        
        for (int p = 0; p < n; p++) {
        	int mJob = -1;
        	for (int j = 0; j < skillList[p].length; j++) {
        		if (skillList[p][j] > mJob) {
        			mJob = skillList[p][j];
        		}
        	}
        	maxJob[p] = mJob + 1;
        	jobstoPeople[p] = -1;
        }
        
        ///////// Initializing jagged arrays /////////
        // No edge: -1; bkwd : 0; fwd: 1
        
        for (int p = 0; p < n; p++) {
        	for (int j = 0; j < skillList[p].length; j++) {
        		int curJob = skillList[p][j];
        		if (curJob == wrongM[p]) {
        		}
        		resPeopleGraph[p] = new int[maxJob[p]]; //MAY BE MXN??
        		for (int i = 0; i < maxJob[p]; i++) {
        			resPeopleGraph[p][i] = -1;
        		}
        	}
        }
        
        ///////// Build Residual Graphs /////////
        
        for (int p = 0; p < n; p++) {
        	for (int j = 0; j < skillList[p].length; j++) {
        		int curJob = skillList[p][j];
        		if (p != source) {
        			// Does not match with pair
        			if (curJob != wrongM[p]) {
        				resPeopleGraph[p][curJob] = 1;
        			}
        			// Matches with pair
        			else {
        				resPeopleGraph[p][curJob] = 0;
        				jobstoPeople[curJob] = p;
        			}
        		}
        		// p is source
        		else {
        			resPeopleGraph[p][curJob] = 1;
        		}
        	}
        }

        /********************************** 
         * Debugging : Print out Jagged Arrays
        ***********************************/
        
//        for (int i = 0; i < n; i++) {
//        	System.out.println(Arrays.toString(resPeopleGraph[i]));
//        }
//
//        for (int i = 0; i < n; i++) {
//        	System.out.println(Arrays.toString(resJobGraph[i]));
//        }
        
        /********************************** 
         * DFS
        ***********************************/
        
        int parent[] = new int [n];
        boolean visitedJob[] = new boolean [n];
        boolean visitedPerson[] = new boolean [n];
        int front;
        int stack[] = new int [Math.max(n*n/2, 2*n)];
        
        ///////// Initializing Arrays /////////
        for (int i = 0; i < n; i++) {
        	parent[i] = -1;
        	visitedJob[i] = false;
        	visitedPerson[i] = false;
        }
        
        ///////// DFS Start /////////
        stack[0] = source;
        front = 0;
        
        boolean atSink = false;
        while (!atSink && front >= 0) {
        	boolean jobLeft = false;
        	int u = stack[front];
        	// Is there any forward paths to jobs?
        	for (int j = 0; j < resPeopleGraph[u].length; j++) {
        		jobLeft = jobLeft || (resPeopleGraph[u][j] == 1 && !visitedJob[j]);
        	}
        	if (jobLeft) {
        		for (int j = 0; j < resPeopleGraph[u].length; j++) {
	        		if (resPeopleGraph[u][j] == 1 && !visitedJob[j]) {
	        			resPeopleGraph[u][j] = -1; // Convert to backwards edge
	        			front++;		//EQUIV : int u = stack.prepend
	        			stack[front] = j; // add job
	        			visitedJob[j] = true;

	        			front++;		  // add person
	        			if (jobstoPeople[j] == -1) { // reach sink
	        				front--; //not going back to the people side
	        				break;
	        			}
	        			stack[front] = jobstoPeople[j]; //getting back to people side
	        			break;
	        		}
	        	}
        	}
        	// Took the wrong path; backtrack two
        	else {
        		stack[front] = -1;
        		front--;
    			if (front < 0) break;
    			visitedJob[stack[front]] = false;
    			stack[front] = -1;
    			front--;
    			if (front < 0) break;
    		}
        	//Reached to sink on the jobs side?
        	atSink = stack[front] == sink && front % 2 != 0;
        }
        
        if (front < 0) existsPerfectMatching = false;
        
        
        /********************************** 
         * Return the Ideal Matching or No
        ***********************************/
        // Array of jobs to be assigned from stack
        int dup[] = new int[n];
        for (int i = 0; i < n; i++) {
        	if (jobstoPeople[i] != -1) {
        		dup[i] = 1;
        	}
        	else dup[i] = -1;
        }
        
        if (!existsPerfectMatching) {
        	output(Args[1]);
        	return;
        }
        
        if (stack[front] == sink) {
        	existsPerfectMatching = true;
        	correctM = new int[n];
        	for (int i = 0; i < n; i++) {
        		correctM[i] = -1;
        	}

        	for (int i = 0; i < front; i += 2) {
    			int node = stack[i];
        		int job = stack[i + 1];
        		correctM[node] = job;
        	}

        	for (int i = 0; i < n; i++) {
        		if (correctM[i] == -1) {
        			correctM[i] = wrongM[i];
        		}
        	}
        }
        
        /********************************** 
         * Check Correctness
        ***********************************/
//        boolean tmatch = true;
//        for (int p = 0; p < correctM.length; p++) {
//        	int job = correctM[p]; 
//        	boolean match = false;
//        	for (int j = 0; j < skillList[p].length; j++) {
//        		match = match || (job == skillList[p][j]);
//        	}
//        	tmatch = tmatch && match;
//        }
//        System.out.println("tmatch: " + tmatch); 
        
        //END OF YOUR CODE

        output(Args[1]);
    }

    public static void main(String [] Args) //Strings in Args are the name of the input file followed by the name of the output file
    {
        new Framework(Args);
    }
    
}
