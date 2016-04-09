/*************************************************************************
 *
 *  Copyright (c) 2015 Cornell University
 *  Computer Systems Laboratory
 *  Cornell University, Ithaca, NY 14853
 *  All Rights Reserved
 *
 *  $Id$
 *
 **************************************************************************
 */
#include "3140_concur.h"
#include <stdlib.h>

/*
  State layout:

  .-----------------.
  |     xPSR   	    | <--- status register
  |-----------------|
  |      PC         | <--- starting point of the process's function    
  |-----------------|
  |      LR         | <--- process_terminated
  |-----------------|
  |      R12        |
  |-----------------|
  |    R3 - R0      |
  |-----------------|
  |   0xFFFFFFF9    | <--- exception return value 
  |-----------------|
  |    R4 - R11     |
  |-----------------|
  |    PIT State    |
  |-----------------|


  State requires 18 slots on the stack.

 */


/*------------------------------------------------------------------------
 *
 *  process_init --
 *
 *   Initialize process state
 *
 *------------------------------------------------------------------------
 */



unsigned int process_init (void (*f)(void), int n)
{
  int *sp;	/* Pointer to process stack (allocated in heap) */
	
	int i;

	/* in reality, there are 18 more slots needed */
	n += 18;
		
  /* Allocate space for the process's stack */
  sp = (int *) malloc(n*sizeof(int));
		 
  if (sp == NULL) { return 0; }	/* Allocation failed */
  
  /* Initialize the stack to all zeros */ 
  /* Note: Could just use calloc instead */ 
  for (i=0; i < n; i++) {
  	sp[i] = 0;
  }
  
	sp[n-1] = 0x01000000; // xPSR
  sp[n-2] = (unsigned int) f; // PC
	sp[n-3] = (unsigned int) process_terminated; // LR
	sp[n-9] = 0xFFFFFFF9; // EXC_RETURN value, returns to thread mode
	sp[n-18] = 0x3; // Enable scheduling timer and interrupt
  
  return (unsigned int)&(sp[n-18]);
}
	
	
}





