type handT = StFlush | ThreeKind | Straight | Flush | Pair | HighCard

SFlush, SFlush -> sFlustTiehandler
SFlush, _ -> hand1
_, SFlush -> hand2
ThreeKind, ThreeKind -> sFlustTiehandler
ThreeKind, _ -> hand1
_, ThreeKind -> hand2


let rec return_three_consec (lst : int list) =
  match List.rev lst with
  | [] -> []
  | x::y::z::t -> if (y = x + 1) && (z = y + 1) then [x;y;z]
                  else return_three_consec (y::z::t)
  | _ -> []



let rec check_three_consec (lst : int list) =
  match lst with
  | [] -> false
  | x::y::z::t -> if (y = x + 1) && (z = y + 1) then true
                  else check_three_consec (y::z::t)
  | _ -> false

let rec check_two_consec (lst : int list) =
  match lst with
  | [] -> false
  | x::y::t -> if (y = x + 1) then true
               else check_three_consec (y::t)
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

let rec check_sflush (hand : card list) =
  match hand with
  | [] -> false
  | x::y::[] -> false
  | h::t -> sflush_helper h hand || check_sflush t

let c1 = make_card 2 "Diamonds"
let c2 = make_card 3 "Spades"
let c3 = make_card 5 "Diamonds"
let c4 = make_card 6 "Spades"
let c5 = make_card 7 "Hearts"
let c6 = make_card 9 "Clubs"


let stflush_tie_handler hand1 hand2 =









  (* append every suit that happens 3 times *)