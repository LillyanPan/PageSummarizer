open OUnit2
open Ast
open TypedAst
open Parse
open Eval
open Infer

let alpha_option =
  parse_variant_spec "type 'a option = Some of 'a | None of unit"

let tests = "test suite" >::: [
  "eval_int"  >::
    (fun _ -> assert_equal
      (VInt 3110)
      (eval [] (Int 3110)));

  "eval_if"   >::
    (fun _ -> assert_equal
      (VInt 15)
      (parse_expr "if false then 3 + 5 else 3 * 5" |> eval []));

  "eval_fun"  >::
    (fun _ -> assert_equal
      (VClosure("x",BinOp(Plus, Int 3, Var "x"),[]))
      (parse_expr "fun x -> 3 + x" |> eval []));

  "infer_inc" >::
    (fun _ -> assert_equal
      (parse_type "int -> int")
      (parse_expr "fun x -> x + 1" |> infer [] |> typeof));

  "infer_variant" >::
    (fun _ -> assert_equal
      (parse_type "int option * string option")
      (parse_expr "(Some 1, Some \"where\")"
       |> infer [alpha_option]
       |> typeof));
]

let _ = run_test_tt_main tests
