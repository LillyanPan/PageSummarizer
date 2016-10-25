open Ast

(******************************************************************************)
(** types (see .mli) **********************************************************)
(******************************************************************************)

type value =
  | VUnit | VInt of int | VBool of bool | VString of string
  | VClosure of var * expr * environment
  | VVariant of constructor * value
  | VPair of value * value
  | VError of string
and environment = (var * value ref) list

(******************************************************************************)
(** (optional) helper functions ***********************************************)
(******************************************************************************)

(** you may find it helpful to implement these or other helper
 * functions, but they are not required. Feel free to implement them if you
 * need them, change their types or arguments, delete them, whatever.
 *)

 (**
  * try to match a value against a pattern. If the match succeeds, return an
  * environment containing all of the bindings. If it fails, return None.
  *)
  (* Compare vairant; check constructure is the same and value
  and should be a recursive call
  Extract the bindings:
  if
  match 15 with
  | 1 -> e1
  | x -> x+15 (bind x to 15 x -> 15) PVar

  match Left Left 15
  | Left Left x -> bind x to 15 (rec call)
   *)

let rec find_match (p : pattern) (v : value) : environment option =
  (* failwith "unimplemented" *)
  match (p, v) with
  | (PUnit, VUnit) -> (Some [])
  | (PInt x1, VInt x2) when x1 = x2 -> (Some [])
  | (PBool x1, VBool x2) when x1 = x2 -> (Some [])
  | (PString x1, VString x2) when x1 = x2 -> (Some [])
  | (PVar x1, v) -> Some [(x1, ref v)]
  (* Must check that either is none otherwise you can join some lists *)
  | (PVariant(c1,p1), VVariant(c2,v1)) when c1 = c2 -> find_match p1 v1
  | (PVar x1, VClosure(x2, e2, en2)) when x1 = x2 -> Some en2
  | (PPair(p1,p2),VPair(v1,v2)) ->
    match (find_match p1 v1), (find_match p2 v2) with
    | Some x, Some y-> (Some (x@y))
    | _ -> None
  | _ -> None

(** apply the given operator to the given arguments *)
let rec eval_operator (op : operator) (v1 : value) (v2 : value) : value =
  failwith "unimplemented"


(** Format a value for printing. *)
let rec format_value (f : Format.formatter) (v : value) : unit =
  (* You will probably want to call Format.fprint f f <format string> <args>.
   *
   * Format.fprintf f <format string> has a different type depeding on the format
   * string. For example, Format.fprintf f "%s" has type string -> unit, while
   * Format.fprintf f "%i" has type int -> unit.
   *
   * Format.fprintf f "%a" is also useful. It has type
   *   (Format.formatter -> 'a -> unit) -> 'a -> unit
   * which is useful for recursively formatting values.
   *
   * Format strings can contain multiple flags and also other things to be
   * printed. For example (Format.fprintf f "result: %i %s") has type
   * int -> string -> unit, so you can write
   *
   *  Format.fprintf f "result: %i %s" 3 "blind mice"
   *
   * to output "result: 3 blind mice"
   *
   * See the documentation of the OCaml Printf module for the list of % flags,
   * and see the printer.ml for some (complicated) examples. Printer, format_type is
   * a nice example.
   *)
  failwith "unimplemented"

(** use format_value to print a value to the console *)
let print_value = Printer.make_printer format_value

(** use format_value to convert a value to a string *)
let string_of_value = Printer.make_string_of format_value

(******************************************************************************)
(** eval **********************************************************************)
(******************************************************************************)

let rec eval env e =
  failwith "unimplemented"
(*   match e with
  | Int _ -> failwith "Does not step"
  | Bool _ -> failwith "Does not step"
  | Var _ -> failwith "Unbound variable"
  (* Strings, Verror *)
  | BinOp(Plus, Int n1, Int n2) -> Int (n1+n2)
  | BinOp(Times, Int n1, Int n2) -> Int (n1*n2)
  | BinOp(LtEq, Int n1, Int n2) -> Bool (n1<=n2)
  | BinOp(Minus, Int n1, Int n2) -> Int (n1-n2)
  | BinOp(Gt, Int n1, Int n2) -> Int (n1>n2)
  | BinOp(Lt, Int n1, Int n2) -> Bool (n1<n2)
  | BinOp(op, Int n1, e2) -> BinOp(op, Int n1, step e2)
  | BinOp(op, e1, e2) -> BinOp(op, step e1, e2)
  | Let(x,Int n,e2) -> subst e2 (Int n) x
  | Let(x,Bool b,e2) -> subst e2 (Bool b) x
  | Let(x,e1,e2) -> Let(x,step e1, e2)
  | If(Bool true, e2, _) -> e2
  | If(Bool false, _, e3) -> e3
  | If(e1,e2,e3) -> If(step e1, e2, e3)
  | Fun(x,e1) -> x
  | App(e1,e2) -> (*TO IMPL*) e2
  (* Pair variant behavior unspecified *)
  | Pair(e1,e2) -> e1
  | Variant(c1,e1) -> e1 *)





