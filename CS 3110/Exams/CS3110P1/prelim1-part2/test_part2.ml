open OUnit2
open Part2

(* This is an extremely minimal and incomplete test suite.
 * If your solution does not at least pass these test cases,
 * exactly as they are written here, then your solution will
 * receive minimal credit. *)
let tests = "test suite for Part 2" >::: [
  "beats" >:: (fun _ ->
    assert_equal true (beats [make_card 10 "Clubs";
                              make_card  9 "Clubs";
                              make_card  8 "Clubs"]
                             [make_card  2 "Hearts";
                              make_card  3 "Diamonds";
                              make_card  7 "Spades"]));
  "can_access" >:: (fun _ ->
    assert_equal [1;2;3]
                 (List.sort Pervasives.compare
                            (can_access [(1,[1;2]); (2,[3])] 1)));
]

let _ = run_test_tt_main tests