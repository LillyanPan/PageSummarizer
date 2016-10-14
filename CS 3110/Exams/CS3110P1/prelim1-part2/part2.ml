(******************************************************************************)
(* PROBLEM 0 *)
(******************************************************************************)

(*
 * Name:  Lillyan Pan
 * NetID: ldp54
 *
 * Academic integrity statement:
 *   Academic integrity is expected of all students at all times, whether in the
 *   presence or absence of members of the faculty.  Understanding this, I
 *   declare by submitting this code that I have not given, used, or received
 *   unauthorized aid in this examination.  I acknowledge that the minimum
 *   penalty for violating academic integrity is a score of zero on the entire
 *   exam.
 *
 * Citations:  Rec 05
 *)

(******************************************************************************)
(* PROBLEM 1 *)
(******************************************************************************)

(* A type that represents a card from a standard 52-card deck,
 * in which the ranks are 2-10, Jack, Queen, King, and Ace, and
 * the suits are Clubs, Diamonds, Hearts, and Spades. *)
exception Not_valid

type rank = Number of int | Jack | Queen | King | Ace

type handT = StFlush | ThreeKind | Straight | Flush | Pair | HighCard
(* type rank = int *)

let int_to_rank num =
  match num with
  | 2 -> Number 2
  | 3 -> Number 3
  | 4 -> Number 4
  | 5 -> Number 5
  | 6 -> Number 6
  | 7 -> Number 7
  | 8 -> Number 8
  | 9 -> Number 9
  | 10 -> Number 10
  | 11 -> Jack
  | 12 -> Queen
  | 13 -> King
  | 14 -> Ace
  | _ -> raise Not_valid

let rank_to_str r =
  match r with
  | Number 2 -> "Two"
  | Number 3 -> "Three"
  | Number 4 -> "Four"
  | Number 5 -> "Five"
  | Number 6 -> "Six"
  | Number 7 -> "Seven"
  | Number 8 -> "Eight"
  | Number 9 -> "Nine"
  | Number 10 -> "Ten"
  | Jack -> "Jack"
  | Queen -> "Queen"
  | King -> "King"
  | Ace-> "Ace"
  | _ -> raise Not_valid

let rank_to_int r =
  match r with
  | Number 2 -> 2
  | Number 3 -> 3
  | Number 4 -> 4
  | Number 5 -> 5
  | Number 6 -> 6
  | Number 7 -> 7
  | Number 8 -> 8
  | Number 9 -> 9
  | Number 10 -> 10
  | Jack -> 11
  | Queen -> 12
  | King -> 13
  | Ace-> 14
  | _ -> raise Not_valid

type suit = Clubs | Diamonds | Hearts | Spades

let str_to_suit str =
  let trim_and_lower_str = String.trim (String.lowercase_ascii str) in
  match trim_and_lower_str with
  | "clubs" -> Clubs
  | "diamonds" -> Diamonds
  | "hearts" -> Hearts
  | "spades" -> Spades
  | _ -> raise Not_valid

let suit_to_str s =
  match s with
  | Clubs -> "Clubs"
  | Diamonds -> "Diamonds"
  | Hearts -> "Hearts"
  | Spades -> "Spades"
  (*GIVE WARNING THAT THIS CASE IS NOT USED; not possible to reach this case*)
  (* | _ -> raise Not_valid *)

type card =
  {rank: rank; suit: suit}

(* [make_card r s] is the value of type [card] whose rank is [r]
 *   and whose suit is [s].  The integers 2-10 represent the card with
 *   the corresponding number on its face, and 11, 12, 13, and 14
 *   represent Jack, Queen, King, and Ace.
 * requires:  [2 <= r <= 14] and [s] is one of ["Clubs"], ["Diamonds"],
 *   ["Hearts"], or ["Spades"].  Note the capitalization and exact spelling
 *   of those strings: they are part of the spec and you may not change them. *)
let make_card (rank:int) (suit:string) : card =
  try
    let var_rank = int_to_rank rank in
    let var_suit = str_to_suit suit in
    {rank = var_rank; suit = var_suit}
  with
  | Not_valid -> failwith "Please enter valid parameters"

(* [string_of_card c] is a string representation of card [c].  The exact
 *   string is unspecified, but this function will be used by the
 *   staff's OUnit test suite in reporting failed test cases. *)
let string_of_card (c:card) : string =
  try
    let str_rank = rank_to_str c.rank in
    let str_suit = suit_to_str c.suit in
    str_rank ^ " of " ^ str_suit
  with
  | Not_valid -> failwith "Please enter valid card"

let rec check_three_consec (lst : int list) =
  match List.rev lst with
  | [] -> []
  | x::y::z::t -> if (y = x + 1) && (z = y + 1) then [x;y;z]
                  else check_three_consec (y::z::t)
  | _ -> []

let rec check_two_consec (lst : int list) =
  match List.rev lst with
  | [] -> false
  | x::y::t -> if (y = x + 1) then true
               else check_three_consec (y::t)
  | _ -> false

let rec check_three_same (lst : int list) =
  match List.rev lst with
  | [] -> false
  | x::y::z::t -> if (y = x) && (z = y) then true
                  else check_three_same (y::z::t)
  | _ -> false

let rec check_two_same (lst : int list) =
  match List.rev lst with
  | [] -> false
  | x::y::t -> if (y = x) then true
                  else check_two_same (y::t)
  | _ -> false


(* takes in suit [s] and list of cards; outputs list of cards all suit s *)
let rec suit_filter s lst =
  match lst with
  | [] -> []
  | h::t -> if h.suit = s then h::(suit_filter s t) else suit_filter s t

(* Takes and card and gives back bool if three consec *)
let sflush_helper card hand =
  let suit_lst = suit_filter card.suit hand in
  if List.length suit_lst >= 3 then
    let rank_int_lst = List.fold_left
      (fun acc card -> (rank_to_int card.rank)::acc) [] suit_lst in
    let sort_int_rank_lst = List.sort compare rank_int_lst in
    check_three_consec sort_int_rank_lst
  else false

let three_kind_helper card hand =
  let rank_int_lst = List.fold_left
      (fun acc card -> (rank_to_int card.rank)::acc) [] hand in
  let sort_int_rank_lst = List.sort compare rank_int_lst in
  check_three_same sort_int_rank_lst

let straight_helper card hand =
  let rank_int_lst = List.fold_left
      (fun acc card -> (rank_to_int card.rank)::acc) [] hand in
  let sort_int_rank_lst = List.sort compare rank_int_lst in
  check_three_consec sort_int_rank_lst

let flush_helper card hand =
  let suit_lst = suit_filter card.suit hand in
  if List.length suit_lst >= 3 then true
  else false

let pair_helper card hand =
  (* MAY NOT NEED IF CHECK *)
  if List.length hand >= 3 then
    let rank_int_lst = List.fold_left
        (fun acc card -> (rank_to_int card.rank)::acc) [] hand in
    let sort_int_rank_lst = List.sort compare rank_int_lst in
    check_two_same sort_int_rank_lst
  else false



let rec check_sflush (hand : card list) =
  match hand with
  | [] -> false
  | x::y::[] -> false
  | h::t -> sflush_helper h hand || check_sflush t

let rec check_three_kind (hand : card list) =
  match hand with
  | [] -> false
  | x::y::[] -> false
  | h::t -> three_kind_helper h hand || check_three_kind t

let rec check_straight (hand : card list) =
  match hand with
  | [] -> false
  | x::y::[] -> false
  | h::t -> straight_helper h hand || check_straight t

let rec check_flush (hand : card list) =
  match hand with
  | [] -> false
  | x::y::[] -> false
  | h::t -> flush_helper h hand || check_flush t

let rec check_pair (hand : card list) =
  match hand with
  | [] -> false
  | x::y::[] -> false
  | h::t -> pair_helper h hand || check_pair t

let match_hand hand =
  if List.length hand >= 3 then
    if check_sflush hand then StFlush
    else if check_three_kind hand then ThreeKind
    else if check_straight hand then Straight
    else if check_flush hand then Flush
    else if check_pair hand then Pair
    else HighCard
  else raise Not_valid



(* [beats h1 h2] is [true] if and only if hand [h1] beats hand [h2]
 *   according to the rules given below.  The two hands may contain
 *   some of the same cards, and there may be duplicate cards within
 *   a single hand.  No assumption may be made about the order of
 *   the cards in the input lists.
 * Requires: the length of [h1] is [3], and so is the length of [h2].
 * A hand [h1] *beats* another hand [h2] if [h1] is *ranked* higher
 *   than [h2], or if they have the same rank and [h1] *wins* according
 *   to the tie breaking rules given below.  Note that the term "rank"
 *   thus applies both to cards and hands, but has a different meaning
 *   for each.
 * The hand ranks, from highest to lowest, are as follows:
 *   - Straight flush:  three cards of the same suit whose ranks are in
 *       consecutive order.  An Ace may count as either rank 14 or rank 1
 *       (so Ace-2-3 and Queen-King-Ace are both in consecutive order),
 *       but the order may not wrap around (so King-Ace-2 is not in
 *       consecutive order).
 *   - Three of a kind:  three cards of the same rank, regardless of suit.
 *   - Straight:  three cards of mixed suits whose ranks are in consecutive
 *       order, as defined above.
 *   - Flush:  three cards of the same suit but whose ranks are not in
 *       consecutive order.
 *   - Pair:  two cards of the same rank and a third card of a different
 *       rank, and that do not all have the same suit.
 *   - High card:  three cards of different ranks, not consecutive, and
 *       not all of the same suit.
 * How to break ties:
 *   - When comparing straight flushes, three-of-a-kinds, and straights,
 *      the hand with the highest-ranked cards wins.  For example,
 *      as straights, 6-5-4 beats 5-4-3.
 *   - When comparing non-straight flushes and high cards, the highest-ranked
 *      cards are compared first and whichever is higher wins; then if those
 *      cards tied, the middle-ranked are compared; and then the lowest.
 *      For example, Ace-7-5 beats King-Queen-Jack.
 *   - When comparing pairs, the rank of the paired cards is compared first,
 *      and whichever is higher wins; then the rank of the third (unpaired)
 *      card is compared.  For example, King-King-5 beats Jack-Jack-Ace.
 * It is possible that even after those tie-breaking rules, two hands
 *   remain tied.  For example, suits are unordered, so a Queen-King-Ace
 *   Heart straight flush would tie a Queen-King-Ace Spade straight flush;
 *   neither beats the other.  In that case, [beats] would return [false]
 *   regardless of which hand is passed in first.
 *)
let beats (hand1 : card list) (hand2 : card list) : bool =
  try
    match (match_hand hand1, match_hand hand2) with
    | (StFlush, StFlush) -> stflush_tie_handler hand1 hand2
    | (StFlush, _) -> true
    | (_, StFlush) -> false
    | (ThreeKind, ThreeKind) -> tkind_tie_handler hand1 hand2
    | (ThreeKind, _) -> true
    | (_, ThreeKind) -> false
    | (Straight, Straight) -> straight_tie_handler hand1 hand2
    | (Straight, _) -> true
    | (_, Straight) -> false
    | (Flush, Flush) -> flush_tie_handler hand1 hand2
    | (Flush, _) -> true
    | (_, Flush) -> false
    | (Pair, Pair) -> pair_tie_handler hand1 hand2
    | (Pair, _) -> true
    | (_, Pair) -> false
    | (HighCard, HighCard) -> hcard_tie_handler hand1 hand2
    | (HighCard, _) -> true
    | (_, HighCard) -> false
    | _ -> raise Not_valid
  with
  | Not_valid -> failwith "Please enter valid parameters"

(******************************************************************************)
(* PROBLEM 2 *)
(******************************************************************************)

(* Definition:  a "set-like list" is a list that
 *   1. may not contain duplicates, and
 *   2. in which order of elements is irrelevant.
 * For example, [1;2;3] is a set-like list and should be considered
 *   the same as the set-like list [3;2;1].  But [1;1] is not
 *   a set-like list.  *)

(* [can_access rooms r] is the set-like list of room identifiers that can be
 *   accessed in zero or more moves by an adventurer who starts in room [r]
 *   on map [rooms].
 * [r] is a room identifier.  Rooms are identified with integers in
 *   this problem, rather than strings (as they were in A2).  Room identifiers
 *   must be greater than or equal to 0.
 * [rooms] represents a "map" (in the sense of a diagram of an area,
 *   not in the sense of a data structure per se).  It is an association
 *   list that binds a room identifier [n] to the set-like list [l] of rooms
 *   that are accessible in exactly one move from [n].  You can think of
 *   [l] as being the exits from [n].  Any room not explicitly bound in a
 *   map is implicitly bound to the empty set-like list.
 * For example, [[(1,[1;2;3]); (2,[2])]] represents a map in which
 *   - room 1 has exits into rooms 1, 2, and 3;
 *   - room 2 has an exit only back into itself;
 *   - room 3 has no exits;
 *   - room 4 (and 5 and ...) has no exits.
 * A *valid* map is one in which no room is bound more than once, and no
 *   exit appears more than once in any binding (which follows from the
 *   requirement that the list be set-like), and all room identifiers are
 *   at least 0.  So [[(1,[1]);(1,[1])]] and [[(1,[1;1])]] and [[1,[-1]]]
 *   are all invalid maps.  Valid maps may contain cycles, such as
 *   [[(1,[1;2]); (2,[1;2])]].
 * Requires:  [rooms] is valid, and [r >= 0].
 * Example usage:  if [rooms] is [[(1,[1;2]); (2,[3])]],
 *   then [can_access rooms 1] is [[1;2;3]],
 *          (or [[3;2;1]] or [[3;1;2]]
 *           or any other permutation of that set-like list),
 *        [can_access rooms 2] is [[2;3]], (or [[3;2]]),
 *        [can_access rooms 3] is [[3]],
 *    and [can_access rooms 4] is [[4]], etc.
 * Performance:  A portion of your grade (around 10-20%) on this problem
 *   will be based on how well your solution scales to large maps. Although
 *   many of the staff's test cases will be small (say, around 10
 *   rooms), we will have a few test cases that get exponentially larger
 *   (100 rooms, 1000, ...?).  The particular timeout we will use is
 *   unspecified but will be on the order of a minute.
 * Advice:  **Use what you have learned about data structures and about
 *   building software.**  There are many possible solutions, including
 *   some simple, inefficient ones that would suffice to get most
 *   of the points for this problem.
 *)
let can_access (rooms: (int * int list) list) (r:int) : int list =
  []  (* TODO: complete [can_access] *)
