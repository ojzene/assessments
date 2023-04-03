# Rubik's Cube Assessment

We need to first consider the physical structure of the cube. The cube consists of six faces, each made up of nine smaller squares, for a total of 54 squares. Each square can be of one of six colors: red, blue, green, yellow, orange, and white.

# Data Structure(s) representing the cube and its parts

A 3D array or matrix can be used to represent a Rubik's Cube in a program. There are six faces to the cube: front, back, left, right, top, and bottom. Each face can be represented by a 2D array or matrix, with each element corresponding to a different colour sticker on the cube. A white face, for example, can be represented by a 2D array in which each element can be one of six different colours (white, red, blue, orange, green, or yellow).

To represent each face of the cube, we can use a data structure. The colours of each face data structure will be saved. Each face can be represented by an array or matrix, with each element corresponding to a different sticker colour. For example, we can represent the cube's top face as follows:

    w w w
    w w w
    w w w	

The letter w stands for white in this context. Similarly, we can represent the cube's other faces. We can store all six cube faces using a 3D array. The initial state of a Rubik's Cube, for example, can be represented as follows:

    front = [[b, b, b], [b, b, b], [b, b, b]]
    back = [[g, g, g], [g, g, g], [g, g, g]]
    left = [[o, o, o], [o, o, o], [o, o, o]]
    right = [[r, r, r], [r, r, r], [r, r, r]]
    top = [[w, w, w], [w, w, w], [w, w, w]]
    bottom = [[y, y, y], [y, y, y], [y, y, y]]		

Here, b stands for blue, g stands for green, o stands for orange, r stands for red, w stands for white, and y stands for yellow.

# To Perform Rotations

To rotate the cube, we can use a set of functions that manipulate the data structure(s) that represent it. For example, to rotate the top face clockwise, rotate the top face matrix by 90 degrees and update the corresponding adjacent faces with the new stickers. A anticlockwise rotation can also be accomplished by rotating the top face matrix by -90 degrees.

To perform a clockwise rotation of the top face, we need to take the elements of the first row of the top face and put them in the last column of the right face, the last row of the top face and put them in the first column of the left face, the first column of the top face and put them in the first row of the back face, and the last column of the top face and put them in the last row of the front face. We also need to rotate the top face matrix by 90 degrees clockwise. Similarly, to perform a counter clockwise rotation of the top face, we need to perform the opposite operations.

# Testing

We can test our proposed solution by writing unit tests that verify the correctness of the cube's state after performing rotations. We can also generate random sequences of rotations and check if the cube returns to its original state. Additionally, we can visualize the cube's state after each rotation using a graphical interface to help verify our solution.

Overall, the choice of data structures and algorithms depends on the specific requirements of the program and the efficiency constraints.
