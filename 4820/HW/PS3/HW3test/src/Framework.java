import java.io.BufferedReader;
import java.io.File;
import java.io.FileReader;
import java.io.PrintWriter;


public class Framework {
	int n;//number of cells. Cells are labeled from 0 to n-1
    int rewards[]; // the reward in each cell
    int colors[];  // the color in each cell
    int score[][]; // score[c][i] means the maximum reward that Alice could collect in the future cells, 
                   // when Alice is at cell i, with color c (before picking the colored number in cell i)
    boolean picked[];//picked[i] means whether Alice has picked i or not
	
		
	//reading the input
	void input(String input_name){
		File file = new File(input_name);
		BufferedReader reader = null;
				
		try {
			reader = new BufferedReader(new FileReader(file));
			
			String text = reader.readLine();
			n=Integer.parseInt(text);
            rewards=new int[n];
            colors=new int[n];

            for (int i=0;i<n;i++){
				text=reader.readLine();
				String [] parts=text.split(" ");
                colors[i]=Integer.parseInt(parts[0]);
                rewards[i]=Integer.parseInt(parts[1]);
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
			
            writer.println(score[0][0]);
            for (int i=0;i<n;i++)
              if (picked[i]) writer.print("1 ");
              else writer.print("0 ");
            writer.println();

			
			writer.close();
		}catch(Exception e){
			e.printStackTrace();
		}
	}
    int getRewards(int AliceColor, int numberColor, int number){
      if (AliceColor==numberColor)
        return number*2;
      else 
        return number;
    }
	
	public Framework(String []Args){
		input(Args[0]);

		
		//YOUR CODE GOES HERE
		//UPDATED ONE
		
		score = new int[3][n];
		picked = new boolean[n];
	
//		Create score[][]
		for (int j = n - 1; j >= 0; j--) {
			for (int c = 0; c < 3; c++) {
				//Set end of matrix
				if (j == n - 1) {
					score[c][j] = getRewards(c, colors[j], rewards[j]);
				}
				else {
					score[c][j] = Math.max(score[c][j+1], score[colors[j]][j+1]
							+ getRewards(c, colors[j], rewards[j]));
					}
				}
			}
//		Create picked[]
		int c = 0;
		for (int i = 0; i < n - 1; i++) { //n-1 to compare last element
			if (score[c][i] == score[c][i+1]) {
				picked[i] = false;
			}
			else {
				picked[i] = true;
				c = colors[i];
			}
		}
		picked[n-1] = true;

		
        //END OF YOUR CODE
		
		output(Args[1]);
	}
	
	public static void main(String [] Args) //Strings in Args are the name of the input file followed by the name of the output file
	{
		new Framework(Args);
	}
}
