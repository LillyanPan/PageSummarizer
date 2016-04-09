import java.util.*;
import java.lang.*;
import java.io.*;
import static java.lang.Math.*;

public class SSandDet {

	public static void main(String[] args) throws FileNotFoundException {
		try {
			FileReader fr = new FileReader("test2.txt");
			BufferedReader input = new BufferedReader(fr);
			int row = Integer.parseInt(input.readLine());
			int[][] matrix = new int[row][row];
			for(int r = 0; r < row; r++) {
				String[] num = input.readLine().split(" ");
				for (int c = 0; c < row; c++) {
					matrix[r][c] = Integer.parseInt(num[c]);
				}
			}
			input.close();
			System.out.println(determinant(matrix));
		}
		catch (Exception ex) {
			System.out.println("The code throws an exception");
			System.out.println(ex.getMessage());
		}
	}

	
	// Sort in ascending order
	public static void selection(int[] b) {
		for (int j = 0;  j < b.length - 1; j++) {
			int min = j;
			for (int i = j + 1; i < b.length; i++) {
				if (b[i] < b[min]) {
					min = i;
				}
				if (min != j) {			// swap
					int temp = b[i];
					b[i] = b[min];
					b[min] = b[temp];
				}
			}
		}
	}

	// Return determinant of nxn matrix
	public static int determinant(int[][] matrix) {
		int det = 0;
		int mlen = matrix.length;

		if (mlen == 1) return matrix[0][0];
		if (mlen == 2) return matrix[0][0] * matrix[1][1] - matrix[1][0] * matrix[0][1];

		for (int i = 0; i < mlen; i++) {
			int[][] small = new int[mlen - 1][mlen - 1];
			for (int r = 1; r < mlen; r++) {
				for (int c = 0; c < mlen; c++) {
					if (c < i) small[r-1][c] = matrix[r][c];
					else if (c > i) small[r-1][c-1] = matrix[r][c];
				}
			}
			det += pow(-1, i) * matrix[0][i] * determinant(small);
		}
		return det;
	}

}
