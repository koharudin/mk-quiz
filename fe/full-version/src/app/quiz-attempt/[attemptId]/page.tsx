"use client";

import api from "@/utils/axios";
import { useEffect, useState } from "react";
import { use } from "react";

export default function Page({ params }: { params: Promise<{ attemptId: string }> }) {
  const { attemptId } = use(params);
  const [data, setData] = useState();
  const loadQuizAttempt = async function () {
    const res = await api.get("/quiz-attempt/" + attemptId)
   // setData(res.data)
  }
  useEffect(() => {
    loadQuizAttempt();
  })
  return <>
    Attempt Quiz Id {attemptId}
    <hr />
    {data}
  </>
}