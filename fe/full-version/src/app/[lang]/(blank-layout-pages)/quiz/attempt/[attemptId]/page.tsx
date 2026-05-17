"use client";

import api from "@/utils/axios";
import { Button, Card, CardContent, CardHeader, Grid2 } from "@mui/material";
import { useEffect, useState } from "react";
import { use } from "react";
import Grid from '@mui/material/Grid2'

// Styles Imports
import frontCommonStyles from '@views/front-pages/styles.module.css'
import classnames from 'classnames'
import Link from "next/link";
import QuizTimer from "../QuizTimer";
// format waktu
const formatTime = (seconds: number) => {
  const hrs = Math.floor(seconds / 3600)
  const mins = Math.floor((seconds % 3600) / 60)
  const secs = seconds % 60

  return `${String(hrs).padStart(2, '0')}:${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`
}
const PanelQuestion = ({ attemptId, selectedQuestionUUID }) => {
  const [dataQuestion, setDataQuestion] = useState()
  const [answeredOptions, setAnsweredOptions] = useState()
  const onLoad = async function () {
    const res = await api.post('/quiz-question/' + selectedQuestionUUID);
    setDataQuestion(res?.data?.question)
    setAnsweredOptions(res?.data?.question?.answer)
  }

  const updateLocalAnswer = function (answer) {
    const quiz_attempt = localStorage.getItem("quiz_attempt_" + attemptId);
    const quiz_attempt_obj = JSON.parse(quiz_attempt);
    const quizQuestions =
      quiz_attempt_obj.quiz_attempt.quiz_questions

    const question = quizQuestions.find(
      q => q.uuid === dataQuestion.uuid
    )

    if (question) {
      question.answer = answer
    }

    localStorage.setItem(
      'quiz_attempt_' + attemptId,
      JSON.stringify(quiz_attempt_obj)
    )
  }
  const onSetAnswer = async (answer) => {
    const res = await api.post('/quiz-question/' + selectedQuestionUUID + "/set-answer", {
      answer: answer
    });

    if (res.status === 200) {
      console.log('Berhasil')
      updateLocalAnswer(answer)
    }
  }


  useEffect(() => {
    if (selectedQuestionUUID) {
      onLoad();
    }
  }, [
    selectedQuestionUUID
  ])
  return <><></>

    <Card>

      <CardContent>
        {!selectedQuestionUUID && <>Pilih Pertanyaan d samping...</>}
        {selectedQuestionUUID &&
          <>{dataQuestion?.question?.name}
            <br></br>
            {dataQuestion?.question?.options.map(function (o, keyo) {
              return <Button onClick={() => {
                onSetAnswer(o.id)
                setAnsweredOptions(o.id)
              }} variant='contained' color={answeredOptions == o.id ? "warning" : ""} className='whitespace-nowrap  mr-2 mb-2' size="small" key={keyo}>{o?.name}</Button>
            })}
          </>
        }
        <hr />

      </CardContent>
    </Card></>
}
const BoardQuestions = ({ questions, selectedQuestionUUID, setSelectedQuestionUUID }) => {
  return questions?.map(function (r, key) {
    return <Button key={key + 1} onClick={() => {
      window.location.hash = 'qid=' + r.uuid
      setSelectedQuestionUUID(r.uuid)
    }}
      variant='contained'
      color={r.answer ? "warning" : "primary"}
      endIcon={r.uuid == selectedQuestionUUID ? <i className='tabler-pencil' /> : ""}
      className='whitespace-nowrap  mr-2 mb-2'
      target='_blank' size="small">{key + 1}</Button>
  });
}
export default function Page({ params }: { params: Promise<{ attemptId: string }> }) {
  const [startTime,setStartTime] = useState();
  const [duration,setDuration] = useState();



  const { attemptId } = use(params);
  const [data, setData] = useState();
  const [selectedQuestionUUID, setSelectedQuestionUUID] = useState();
  const loadQuizAttempt = async function () {
    const res = await api.get("/quiz-attempt/" + attemptId)
    localStorage.setItem(
      'quiz_attempt_' + attemptId,
      JSON.stringify(res.data)
    )
    setStartTime(res?.data?.quiz_attempt?.start_time)
    setDuration(res?.data?.quiz_attempt?.duration)
    setData(res.data)
  }

  const [qid, setQid] = useState('')


  useEffect(() => {
    const updateHash = () => {
      const params = new URLSearchParams(
        window.location.hash.substring(1)
      )

      setQid(params.get('qid') || '')
    }

    updateHash()

    window.addEventListener('hashchange', updateHash)

    return () => {
      window.removeEventListener('hashchange', updateHash)
    }
  }, [])

  useEffect(() => {
    setSelectedQuestionUUID(qid);
  }, [qid])
  useEffect(() => {
    loadQuizAttempt();
  }, [])
  return <section className={classnames('md:plb-[100px] plb-6', frontCommonStyles.layoutSpacing)}>

    <Card>
      <CardHeader title={<QuizTimer start_time={startTime} duration={duration}/>}>

      </CardHeader>
      <CardContent>
        <Grid container spacing={6}>
          <Grid size={{ xs: 6 }}>

            <PanelQuestion attemptId={attemptId} selectedQuestionUUID={selectedQuestionUUID}></PanelQuestion>
          </Grid>
          <Grid size={{ xs: 6 }}>
            <BoardQuestions attempt_uuid={attemptId} setSelectedQuestionUUID={setSelectedQuestionUUID} selectedQuestionUUID={selectedQuestionUUID} questions={data?.quiz_attempt?.quiz_questions}></BoardQuestions>
          </Grid>

        </Grid>
      </CardContent>
    </Card>


  </section >
}