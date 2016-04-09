HW 1 Binary Search

The following code searches a sorted array for the value v and returns its index i if found, or -1 if the value is not found. Specifically, when i != j, we calculate the average (h) of indicies i,j and determine which of the two subarrays to search for value v by comparing a[h] to v. It then does a recursive call to search the chosen subarray.

;R0 = return value
;R1 = first element pointer; base
;R2 = i
;R3 = j
;R7 = v
;R4 = h
;R5 = a[h]
;R8 = 4

begin
	PUSH {R4-R8}
    MOV R8, #4
	MOV R1, a_addr 		; store address array in R1
	MOV R2, #i
	MOV R3, #j
	MOV R7, #v

loop
	CMP R2, R3
	BEQ eqcheck
	SUM R4, R2, R3		; calculates h
	ASR R4, #1			; divide by 2
    PUSH {R4}           ; push to get correct address (*4)
    MUL R4, R4, R8
    LDR R5, [R1, R4]	; a[h] word h*4
    POP {R4}            ; pop to get back h
    CMP R5, R7			; a[h] > v
	BEQ hequal
	BGT greater
	BLT less
hequal
	MOV R0, R7
	B end
eqcheck
    PUSH {R2}
    MUL R2, R2, R8
	LDR R5, [R1, R2]	; load a[i]
    POP {R2}
    CMP R5, R7			; (a[i] == v) ?
	BNE noMatch
	BEQ match
noMatch
	MOV R0, #0
	SUB R0, RO, #1		; R0 returns -1
	B end
match
	MOV R0, R2
	B end
greater
	MOV R3, R5			; j = h
	B loop
less
	ADD R5, R5, #1		; h+1
	MOV R2, R5			; i = h+1
	B loop
end
	POP {R4-R7}