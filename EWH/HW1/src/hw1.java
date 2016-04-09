import java.util.*;
import java.lang.*;
import java.io.*;
import static java.lang.Math.*;

import java.awt.Point;

public class hw1 {

	public static void main(String[] args) throws Exception {
		// Overlaps
		FileReader fr = new FileReader("test3.txt");
		BufferedReader input = new BufferedReader(fr);
		
		int minX1 = 20, minY1 = 20;
		int maxX1 = 0, maxY1 = 0;
		int minX2 = 20, minY2 = 20;
		int maxX2 = 0, maxY2 = 0;

		try {
			int row = Integer.parseInt(input.readLine());
			int s1[][] = new int[row][4];
					for(int r = 0; r < row; r++) {
						String[] num = input.readLine().split(" ");
						for (int c = 0; c < 4; c++) {
							s1[r][c] = Integer.parseInt(num[c]);
							int coord = s1[r][c];
							if (c % 2 == 0) {
								if (coord < minX1) minX1 = coord;
								if  (coord > maxX1) maxX1 = coord;
							}
							else {
								if (coord < minY1) minY1 = coord;
								if  (coord > maxY1) maxY1 = coord;
							}
						}
					}
					System.out.println("Shape 1:");
					for(int i = 0; i < row; i++){
						for(int j = 0; j < 4; j++){
							System.out.print(s1[i][j] + " ");
						}
						System.out.println();
					}
					
					System.out.println();
					System.out.println("Shape 2:");
					row = Integer.parseInt(input.readLine());
					int [][] s2 = new int[row][4];
					for(int r = 0; r < row; r++) {
						String[] num = input.readLine().split(" ");
						for(int c = 0; c < 4; c++) {
							s2[r][c] = Integer.parseInt(num[c]);
							int coord = s2[r][c];
							if (c % 2 == 0) {
								if (coord < minX2) minX2 = coord;
								if  (coord > maxX2) maxX2 = coord;
							}
							else {
								if (coord < minY2) minY2 = coord;
								if  (coord > maxY2) maxY2 = coord;
							}
				}
			}
			for(int i = 0; i < row; i++){
				for(int j = 0; j < 4; j++){
					System.out.print(s2[i][j] + " ");
				}
				System.out.println();
			}
			
			//Bounding box
				int maxX = Math.min(maxX1, maxX2);
				int minX = Math.max(minX1, minX2);
				int maxY = Math.min(maxY1, maxY2);
				int minY = Math.max(minY1, minY2);

				ArrayList<Point> inter = new ArrayList<Point>();
				int seg = 0;
				for (int y = minY; y < maxY; y++) {
					for (int x = minX; x < maxX; x++) {	
						if (inSeg(x, y, s1[seg][0], s1[seg][1], s1[seg][2], s1[seg][3]) || 
								inSeg(x, y, s2[seg][0], s2[seg][1], s2[seg][2], s2[seg][3])) {
							inter.add(new Point(x,y));
						}
					}
				}
				System.out.println("Overlapping points:");
				for (int i = 0; i < inter.size(); i++) {
					System.out.println("(" + inter.get(i).x + "," + inter.get(i).y + ")");
				}
				
			
		}
		catch (Exception ex) {
			System.out.println("The code throws an exception");
			System.out.println(ex.getMessage());
		}
		input.close();
	}

	public static boolean inSeg(int x, int y, int x1, int y1, int x2, int y2) {
		if (y == y1 && x == x1 || y == y2 && x == x2) {
			return false;
		}
		
		if (x2 - x1 != 0) {
			double slope = (y2 - y1) / (x2 - x1);
			if (slope != 0) {
				double yinter = y1 - slope * x1;
				double yplot = (slope * x) + yinter;
				if (x > Math.min(x1, x2) && x < Math.max(x1, x2) && yplot > Math.min(y1, y2) && yplot < Math.max(y1, y2)) {
					return true;
				}
			}
			else if (slope == 0) {
				System.out.println(x + " " + y);
				if (x > Math.min(x1, x2) && x < Math.max(x1, x2) && y > y1) {
					return true;
				}
			}
			}
		
		return false;
	}
}