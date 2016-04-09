/* The Point class Header file (Point.h) */
#ifndef POINT_H
#define POINT_H
 
class Point {
private:
   int x, y;  // Private data members
 
public:
   Point(int x = 0, int y = 0); // Constructor with default arguments
   int getX() const;  // Getter
   void setX(int x);  // Setter
   int getY() const;
   void setY(int y);
   void setXY(int x, int y);
   void print() const;
};
 
#endif