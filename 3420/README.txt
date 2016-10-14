Pin Connections

LED Matrix:
VCC = 5V
GND = GND
DIN = PTD2
CLK = PTD1
CS = PTA2

To configure the LED Matrix:
Change scan limit to all columns (write 0x07)
Change to "no decode" mode
Change to normal mode, not shutdown mode
Set to normal mode, not test mode
Set LED brightness
Empty all registers; turn all LEDs off


7-Segment Display:
Segment A = Pin 9 (D9)
Segment B = Pin 2 (D2)
Segment C = Pin 3 (D3)
Segment D = Pin 5 (D5)
Segment E = Pin 6 (D6)
Segment F = Pin 8 (D8)
Segment G = Pin 7 (D7)
Decimal Point (DP) = Pin 4 (D4)
Digit 1 = Pin 10 (D10)
Digit 2 = Pin 11 (D11)
Digit 3 = Pin 12 (D12)
Digit 4 = Pin 13 (D13)

More specifically,

Left (Bottom):
PTC5 - Seg E (Pin 1)
PTC7 - Seg D (Pin 2)

PTC9 - Decimal (Pin 3)
PTC8 - Seg C (Pin 4)

PTB19 - Seg G (Pin 5)
PTB18 - D4 (Pin 6)
Right (Top):
PTC3 - D1 (Pin 12)
PTC2 - Seg A (Pin 11)

PTB23 - Seg F (Pin 10)
PTA1 - D2 (Pin 9)

PTC17 - D3 (Pin 8)
PTC16 - Seg B (Pin 7)

PTC2 - Seg A (Pin 11)
PTC16 - Seg B (Pin 7)
PTC8 - Seg C (Pin 4)
PTC7 - Seg D (Pin 2)
PTC5 - Seg E (Pin 1)
PTB23 - Seg F (Pin 10)
PTB19 - Seg G (Pin 5)
PTC3 - D1 (Pin 12)
PTA1 - D2 (Pin 9)
PTC17 - D3 (Pin 8)
PTB18 - D4 (Pin 6)

To write to the 4-digit, 7-segment display:
To light a digit, drive the corresponding digit pin to LOW (PTB->PCOR)
To light a specific segment, drive corresponding segment pin to HIGH (PTC->PSOR)
