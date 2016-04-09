//
//  process.c
//  
//
//  Created by Lillyan Pan on 3/22/16.
//
//

#include <stdio.h>
#include "3140_concur.h"
#include "3140.s" //??

int redLEDFlag = 0; // 0 means off; 1 means on
int blueLEDFlag = 0; // 0 means off

void LEDRed_On(void) {
    PTB->PCOR = (1 << 22); //cleared to logic level 0 which turns LED ON
    redLEDFlag = 1;
}

void LEDRed_Off (void) {
    PTB->PSOR = (1 << 22); //Corresponding bit in PDORn is set to logic 1.
    redLEDFlag = 0;
}

void LEDBlue_On (void) {
    PTE->PCOR = (1 << 22);
    blueLEDFlag = 1;
}

void LEDBlue_Off (void) {
    PTE->PSOR = (1 << 22);
    blueLEDFlag = 0;
}

void LEDBlue_Toggle (void) {
    PTE->PTOR = (1 << 22);
}

void LEDRed_Toggle (void) {
    PTB->PTOR = (1 << 22);
}

typedef struct process_state {
    unsigned int sp;
    /* the stack pointer for the process */
    
} process_t;

struct mylist {
    int val;
    struct mylist *next;
};
//ADD

void append( process_t elem ) { //?
    struct mylist *tmp;
    if (list_start == NULL) {
        list_start = elem;
        elem->next = NULL;
    }
    else {
        tmp = list_start;
        while (tmp->next) {
            /* while there are more elements in the list */
            tmp = tmp->next;
        }
        /* now tmp is the last element in the list */
        tmp->next = elem;
        elem->next = NULL;
    }
}

void remove( process_t elem ) { //?
    if (list_start == NULL) {
        elem = NULL;
    }
    else {
        elem = list_start;
        list_start = list_start->next;
    }
}


//TODO: accept proccess
typedef struct process_state {
    unsigned int sp;   /* the stack pointer for the process */
} process_t;

extern unsigned int process_select (unsigned int cursp) {
    if (cursp == 0) {
        if (process_queue == NULL) { // no processes ready
            return 0;
        }
        else {
            process_t* p = remove( process_queue ); //first element
        }//return first one or if process queue is null/empty
    }
    else {
        process_queue.append(current_process);
        process_t* p = remove( process_queue );
        return p;
    }
    
}

void process_start (void) {
    //TODO
    //If queue is empty the whole time then make a new queue
    
    SIM->SCGC5 |= SIM_SCGC5_PORTB_MASK; //Enable the clock to port B
    SIM->SCGC5 |= SIM_SCGC5_PORTE_MASK; //Enable the clock to port E
    
    PORTB->PCR[22] = PORT_PCR_MUX(001); //Set up PTB18 as GPIO
    PORTE->PCR[26] = PORT_PCR_MUX(001); // Set up green LED, Set up PTE18 as GPIO
    PTB->PDDR = (1 << 22);
    
    PORTB->PCR[21] = PORT_PCR_MUX(001); // Set up blue LED, Set up PTB18 as GPIO
    PTB->PDDR = (1 << 21); //Shift 1 to bit 21
    PIT->CHANNEL[0].LDVAL = 0x30000; //Set the load value of the zeroth PIT
    SIM->SCGC6 = SIM_SCGC6_PIT_MASK; //Enable the clock to the PIT module
    LEDBlue_Off();
    LEDRed_Off();
    PIT->MCR = 0x00; //Enable PIT
    process_begin();
}

int process_create (void (*f)(void), int n) {
    //TODO
    int ptr = process_init ((*f),n);
    // Process failed to allocate memory
    if (ptr == 0) {
        return -1;
    }
    else {
        process_t* p = malloc(n);
        p.sp = ptr;
        process_queue.append(p);
    }
    
    
    
}